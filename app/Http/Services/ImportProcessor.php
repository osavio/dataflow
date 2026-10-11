<?php

namespace App\Http\Services;

use App\Enums\ImportItemStatus;
use App\Enums\ImportStatus;
use App\Models\Import;
use App\Models\ImportItem;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ImportProcessor
{
    private const CHUNK_SIZE = 500;
    private const DELIMITER = ';';

    public function process(Import $import): void
    {
        $import->update(['status' => ImportStatus::Processing, 'error_message' => null]);

        $import->items()->delete();

        $stream = Storage::disk('s3')->readStream($import->file_path);

        if (! is_resource($stream)) {
            throw new RuntimeException("File not found: {$import->file_path}");
        }

        try {
            $header = $this->readHeader($stream);
            $batch = [];

            while (($row = $this->readRow($stream)) !== false) {
                if ($row === [null]) { 
                    continue;
                }

                $batch[] = $this->buildItem($import, $header, $row);

                if (count($batch) >= self::CHUNK_SIZE) {
                    ImportItem::insert($batch);
                    $batch = [];
                }
            }
            if ($batch !== []) {
                ImportItem::insert($batch);
            }
        } finally {
            fclose($stream);
        }

        $import->update(['status' => ImportStatus::Completed]);
    }

    private function readRow($stream): array|false
    {
        return fgetcsv($stream, null, self::DELIMITER, '"', '');
    }

    private function readHeader($stream): array
    {
        $header = $this->readRow($stream);

        if ($header === false || $header === [null]) {
            throw new RuntimeException('Empty CSV or CSV without a header.');
        }

        // Remove o BOM UTF-8 que o Excel costuma colocar no início do arquivo
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        return array_map('trim', $header);
    }

    private function buildItem(Import $import, array $header, array $row): array
    {
        $valid = count($row) === count($header);
        $now = now();

        $data = $valid ? array_combine($header, $row) : ['raw' => $row];

        return [
            'import_id'     => $import->id,
            'data'          => json_encode($data, JSON_THROW_ON_ERROR),
            'status'        => ($valid ? ImportItemStatus::Completed : ImportItemStatus::Failed)->value,
            'erro_message' => $valid ? null : 'Number of columns differs from the header.',
            'created_at'    => $now,
            'updated_at'    => $now,
        ];
    }
}
