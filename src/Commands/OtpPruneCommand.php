<?php

namespace Ibrahemkamal\Otp\Commands;

use Ibrahemkamal\Otp\Models\OtpCode;
use Illuminate\Console\Command;

class OtpPruneCommand extends Command
{
    protected $signature = 'otp:prune {--expired : whether to delete expired otp codes or not} {--verified : whether to delete verified otp codes or not}';

    protected $description = 'Prune expired or verified otp codes from the database.';

    public function handle(): void
    {
        $expired = $this->option('expired');
        $verified = $this->option('verified');


        if (!$expired && !$verified) {
            $this->error('You must use either expired or verified option or both.');
            return;
        }

        $query = OtpCode::query();
        if ($expired) {
            $query->where('expires_at', '<', now());
        }
        if ($verified) {
            $query->orWhereNotNull('verified_at');
        }
        $query->delete();
        $this->info('Otp codes pruned successfully.');
    }
}
