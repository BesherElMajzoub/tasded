<?php

namespace App\Console\Commands;

use App\Models\ContactClick;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportOfflineConversions extends Command
{
    protected $signature = 'landing:export-conversions
        {refs* : Reference codes of qualified leads (from the WhatsApp message)}
        {--value= : Optional conversion value}
        {--currency=SAR : Conversion currency}';

    protected $description = 'Mark leads as qualified and export a Google Ads offline conversion CSV';

    public function handle(): int
    {
        $refs = collect($this->argument('refs'))->map(fn (string $ref) => strtoupper(trim($ref)))->unique();
        $clicks = ContactClick::whereIn('ref', $refs)->get()->keyBy('ref');

        foreach ($refs->diff($clicks->keys()) as $missing) {
            $this->warn("Reference {$missing} was not found.");
        }

        $rows = [];

        foreach ($clicks as $click) {
            $click->qualified_at ??= now();
            $click->save();

            if (! $click->gclid) {
                $this->warn("Reference {$click->ref} has no gclid (not from a Google ad click); skipped.");

                continue;
            }

            $rows[] = [
                $click->gclid,
                config('landing.offline_conversion_name'),
                $click->qualified_at->timezone('Asia/Riyadh')->format('Y-m-d H:i:s'),
                $this->option('value') ?? '',
                $this->option('currency'),
            ];
        }

        if ($rows === []) {
            $this->error('No conversions to export.');

            return self::FAILURE;
        }

        $lines = [
            'Parameters:TimeZone=Asia/Riyadh',
            'Google Click ID,Conversion Name,Conversion Time,Conversion Value,Conversion Currency',
            ...array_map(fn (array $row) => implode(',', array_map($this->csvField(...), $row)), $rows),
        ];

        $path = 'conversions/conversions-'.now()->format('Ymd-His').'.csv';
        Storage::disk('local')->put($path, implode("\n", $lines)."\n");

        $this->info(count($rows).' conversion(s) exported to '.Storage::disk('local')->path($path));

        return self::SUCCESS;
    }

    private function csvField(string $value): string
    {
        return preg_match('/[",\n]/', $value) ? '"'.str_replace('"', '""', $value).'"' : $value;
    }
}
