<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-8-31 下午7:10
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */

namespace LaravelLicense\Command;

use Illuminate\Console\Command;

class Encode extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'entrust:encode {input_file} {output_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command format entrust:encode {input_file} {output_file}';


    private $code = 'ICRzb3VyY2UgPSBzdHJfcmVwbGFjZSgnPD9waHAnLCAnJywgc' .
    'GhwX3N0cmlwX3doaXRlc3BhY2UoJGlucHV0X2ZpbGUpKTsgZmlsZV9wdXRfY29ud' .
    'GVudHMoICRvdXRwdXRfZmlsZSwgb3BlbnNzbF9lbmNyeXB0KCRzb3VyY2UsICdBRVMy' .
    'NTYnLCAnMGRidnRVRlV4U2I2RDl6TFZmSkUwR3FLaEk4ZkMnLCAwLCAndHE2cW92bnZxMmxrdTQzcCcpICk7';

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
        $input_file = $this->argument('input_file');
        $output_file = $this->argument('output_file');

        if (file_exists($input_file)) {
            eval(base64_decode($this->code));
            $this->info("Encode to file {$output_file} complete.\n");
        } else {
            $this->error("File {$input_file} not exist.\n");
        }


    }
}
