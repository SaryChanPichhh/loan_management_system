<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanSchedule;
use App\Models\Notification;
use Carbon\Carbon;

class GenerateDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate notifications for installments due in 2 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDate = Carbon::today()->addDays(2);
        
        $schedules = LoanSchedule::with(['loan.customer'])
            ->where('status', '!=', 'paid')
            ->whereDate('due_date', $targetDate)
            ->get();

        $count = 0;
        foreach ($schedules as $schedule) {
            $customer = $schedule->loan->customer;
            
            // Avoid duplicate notifications for the same schedule
            $exists = Notification::where('customer_id', $customer->id)
                ->where('message', 'like', "%Installment #{$schedule->installment_number} of%")
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$exists) {
                Notification::create([
                    'title' => 'ការរំលឹកការបង់ប្រាក់ (Payment Due Reminder)',
                    'message' => "Loan {$schedule->loan->loan_code}: Installment #{$schedule->installment_number} of {$schedule->amount_due} is due in 2 days ({$schedule->due_date->format('d M, Y')}).",
                    'type' => 'warning',
                    'is_read' => false,
                    'customer_id' => $customer->id,
                ]);
                $count++;
            }
        }

        $this->info("Successfully generated {$count} due reminders.");
    }
}
