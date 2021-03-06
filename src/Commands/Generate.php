<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-8-31 下午8:54
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */

namespace LaravelLicense\Command;

use Illuminate\Console\Command;

class Generate extends Command
{

    private $default_iv = 'HpeuYZvltqXYw2Ew';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:generate {date} {--I|iv=HpeuYZvltqXYw2Ew}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command format entrust:generate {date} -P=xxx|--password=xxxx -I=xxx|--iv=xxxx';


    private $code1 = 'PDfvEqgePxsS8H201VeVtxQObvybyi4MS0h2pI+cBK6uyb3IatDciWFm0lPoqaOYO0YrYFNDQR23/oD20' .
    'mgWdb6JZQgXTWfIFElEmAR73O9an4RcWf+JlYWOZiQURz79eP6pDz6hBqXhhc4AHvhejUSZp7dYFrWgtUARGpZL7ls=';

    private $code2 = 'ZnVuY3Rpb24gY2FsbF9teV9hYmNkZWZnKCRhYmNkZWZnKSB7IGV2YWwoJGFiY2RlZmcpOyB9ICRhYSA9ICdt' .
    'ZDUnOyAkYWFhID0gJ29wZW5zc2xfZGVjcnlwdCc7ICRhID0gJ2NhbGxfbXlfYWJjZGVmZyc7ICRhYSA9ICRhYWEoJHRoaXMtPmN' .
    'vZGUxLCAnQUVTMjU2JywgJGFhKCR0aGlzLT5kZWZhdWx0X2l2KSwgMCwgJHRoaXMtPmRlZmF1bHRfaXYpOyAkYSgkYWEpOw==';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command for Laravel 5.5+.
     *
     * @return void
     */
    public function handle()
    {
        global $date, $iv, $result;
        $result = '';
        $expire_date = $this->argument('date');
        $iv = $this->option('iv');

        $date = date('Y-m-d', strtotime($expire_date));

        if (strlen($iv) != 16) {
            $iv = $this->default_iv;
        }
        eval(base64_decode($this->code2));
        $this->info("Using iv:{$iv} to generate date:{$date}, result:\n{$result}\n");

    }
}
