<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-8-31 下午1:48
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */

namespace LaravelLicense\Composer;

use Composer\Autoload\AutoloadGenerator;
use Composer\Composer;
use Composer\Installer\InstallationManager;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;


class AutoloadFilesGenerator extends AutoloadGenerator
{

    /**
     * @param \Composer\Composer
     * @param string
     * @param string
     *
     * @see https://github.com/composer/composer/blob/master/src/Composer/Autoload/AutoloadGenerator.php#L115
     */
    public function dumpFiles(Composer $composer, $targetDir = 'composer', $suffix = '', $staticPhpVersion = 70000)
    {
        $installationManager = $composer->getInstallationManager();
        $localRepo = $composer->getRepositoryManager()->getLocalRepository();
        $mainPackage = $composer->getPackage();
        $config = $composer->getConfig();
        $filesystem = new Filesystem();
        $filesystem->ensureDirectoryExists($config->get('vendor-dir'));
        // Do not remove double realpath() calls.
        // Fixes failing Windows realpath() implementation.
        // See https://bugs.php.net/bug.php?id=72738
        $basePath = $filesystem->normalizePath(realpath(realpath(getcwd())));
        $vendorPath = $filesystem->normalizePath(realpath(realpath($config->get('vendor-dir'))));
        $targetDir = $vendorPath . '/' . $targetDir;
        $filesystem->ensureDirectoryExists($targetDir);
        $vendorPathCode = $filesystem->findShortestPathCode(realpath($targetDir), $vendorPath, true);
        $vendorPathCode52 = str_replace('__DIR__', 'dirname(__FILE__)', $vendorPathCode);
        $appBaseDirCode = $filesystem->findShortestPathCode($vendorPath, $basePath, true);
        $appBaseDirCode = str_replace('__DIR__', '$vendorDir', $appBaseDirCode);
        // Collect information from all packages.
        $packageMap = $this->buildPackageMap($installationManager, $mainPackage, $localRepo->getCanonicalPackages());
        $autoloads = $this->parseAutoloads($packageMap, $mainPackage);
        if (!$suffix) {
            if (!$config->get('autoloader-suffix') && is_readable($vendorPath . '/autoload.php')) {
                $content = file_get_contents($vendorPath . '/autoload.php');
                if (preg_match('{ComposerAutoloaderInit([^:\s]+)::}', $content, $match)) {
                    $suffix = $match[1];
                }
            }
            if (!$suffix) {
                $suffix = $config->get('autoloader-suffix') ?: md5(uniqid('', true));
            }
        }
        $files = [];
        foreach ($autoloads['files'] as $key => $file) {
            if (stripos($file, 'joseph-bing-han/laravel-license-entrust') !== false) {
                $files = array_merge([$key => $file], $files);
            } else {
                $files[$key] = $file;
            }
        }
        $includeFilesFilePath = $targetDir . '/autoload_files.php';
        if ($includeFilesFileContents = $this->getIncludeFilesFile($files, $filesystem, $basePath, $vendorPath, $vendorPathCode52, $appBaseDirCode)) {
            file_put_contents($includeFilesFilePath, $includeFilesFileContents);
            file_put_contents($targetDir.'/autoload_static.php', $this->getStaticFile($suffix, $targetDir, $vendorPath, $basePath, $staticPhpVersion));
        } elseif (file_exists($includeFilesFilePath)) {
            unlink($includeFilesFilePath);
        }
    }
}