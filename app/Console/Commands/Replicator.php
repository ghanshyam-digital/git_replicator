<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class Replicator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git_replicator:replicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Git replicator';

    /**
     * Error variable.
     *
     * @var bool
     */
    protected $error = false;

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     */
    public function handle()
    {
        $this->info('Checking config.json file.');
        $this->call('git_replicator:validate_config');

        $config = json_decode(file_get_contents(base_path('config.json')), true);

        if (!empty($config['global']['repositoriesLocation'])) {
            // @todo - to think on how to validate repo.+
            $reposDir = $config['global']['repositoriesLocation'];
        } else {
            $reposDir = dirname(base_path()) . ds() . 'repos';
        }

        if (!is_dir($reposDir)) {
            if (mkdir($reposDir) === false) {
                throw new Exception("Repository Directory (" . $reposDir . ") is not writable.");
            }
        }

        chdir($reposDir);

        if (is_array($config['repositories'])) {
            foreach ($config['repositories'] as $index => $repo) {
                if ($index > 0) {
                    $this->info('************************************************************************');
                }

                if (!empty($repo['source']) && !empty($repo['destination'])) {
                    $sourceCreds = array_merge([], $config['global']['source']['credentials'] ?? [], $repo['source']['credentials'] ?? []);
                    $sourceUrl = $repo['source']['url'];
                    $this->info("Processing $sourceUrl");
                    $sourceFolder = md5($sourceUrl);
                    $sourceFolderPath = $reposDir . ds() . $sourceFolder;
                    $sourceUrlParsed = parse_url($sourceUrl);
                    $sourceUrlParsed['user'] = $sourceCreds['username'];
                    $sourceUrlParsed['pass'] = $sourceCreds['password'];
                    $sourceUrl = unparse_url($sourceUrlParsed);

                    if (!is_dir($sourceFolderPath)) {
                        $this->comment('Source repo Doesn\'t exits, Cloning Source repo.');
                        $cloneString = 'git clone --mirror ' . $sourceUrl . ' ' . $sourceFolder;
                        $out = $this->exec($cloneString);
                        if ($this->error) {
                            $this->error($out);
                            $this->error('Skipping...');
                            continue;
                        } else {
                            $this->comment($out);
                        }
                        chdir($sourceFolderPath);
                    } else {
                        $this->comment('Source repo found. Fetching changes...');
                        chdir($sourceFolderPath);
                        $pullString = 'git fetch -p origin';
                        $out = $this->exec($pullString);
                        $this->comment($out);

                    }
                    $this->comment('Changing directory to: ' . $sourceFolderPath);

                    foreach ($repo['destination'] as $destination) {
                        eko();
                        $this->comment('Processing Destination : ' . $destination['url']);

                        $destinationCreds = array_merge([], $config['global']['destination']['credentials'] ?? [], $destination['credentials'] ?? []);
                        $destinationUrl = $destination['url'];
                        $destinationName = md5($destinationUrl);
                        $destinationUrlParsed = parse_url($destinationUrl);
                        $destinationUrlParsed['user'] = $destinationCreds['username'];
                        $destinationUrlParsed['pass'] = $destinationCreds['password'];
                        $destinationUrl = unparse_url($destinationUrlParsed);

                        // Check if remote URL is already added or not.
                        $out = $this->exec('git remote');
                        if (stripos($out, $destinationName) === false) {
                            $this->info("Adding remote url: " . $destination['url']);
                            $this->exec('git remote add ' . $destinationName . ' ' . $destinationUrl);
                            $this->info("Remote url Added");
                        } else {
                            $this->info("Remote url: " . $destination['url'] . ' Already Exists.');
                        }

                        $this->info("Pushing changes to Remote");
                        $out = $this->exec('git push ' . $destinationName . '  --mirror');
                        $this->comment($out);
                        $this->info("Pushed to Destination");
                        eko();
                    }
                }
            }
        }
    }

    /**
     * Execute a command in shell.
     * @param $command Command
     * @param bool $return Default to TRUE, TRUE to return output. make FALSE to echo output to console.
     * @return string
     */
    private function exec($command, $return = true)
    {
        $this->error = false;
        if ($return) {
            exec(escapeshellcmd($command) . ' 2>&1', $o, $return);

            if ($return !== 0) {
                $this->error = true;
            }
            return implode(PHP_EOL, $o);
        } else {
            exec(escapeshellcmd($command));
        }
    }

}
