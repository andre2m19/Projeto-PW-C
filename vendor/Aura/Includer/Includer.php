<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 *
 */
namespace Aura\Includer;

/**
 *
 * Includes multiple files from specified directories, in order, with
 * variables extracted into a limited include scope.
 *
 * @todo add a debug/log/tracker so we can see what didn't get loaded
 *
 * @package Aura.Includer
 *
 */
class Includer
{
    /**
     *
     * Process paths directory-first.
     *
     * @const string
     *
     */
    const DIR_ORDER = 'dir_order';

    /**
     *
     * Process paths file-first.
     *
     * @const string
     *
     */
    const FILE_ORDER = 'file_order';

    /**
     *
     * The location of the cached include file, if any.
     *
     * @var string
     *
     */
    protected $cache_file;

    /**
     *
     * Debug information generated by `getPaths()`, etc.
     *
     * @var array
     *
     */
    protected $debug = array();

    /**
     *
     * The directories to traverse for files.
     *
     * @var array
     *
     */
    protected $dirs = array();

    /**
     *
     * The files to look for in the directories.
     *
     * @var array
     *
     */
    protected $files = array();

    /**
     *
     * A closure to include files in a limited scope.
     *
     * @var Closure
     *
     */
    protected $limited_include;

    /**
     *
     * Variables to extract within the limited include.
     *
     * @var array
     *
     */
    protected $vars = array();

    /**
     *
     * Operate in strict mode?  When strict, the combined directory and file
     * path must be readable via realpath() and must be within the directory.
     *
     * @var bool
     *
     */
    protected $strict = true;

    /**
     *
     * The found paths.
     *
     * @var array
     *
     */
    protected $paths = array();

    /**
     *
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->limited_include = function ($__FILE__, array $__VARS__) {
            unset($__VARS__['__FILE__']);
            extract($__VARS__);
            unset($__VARS__);
            include $__FILE__;
        };
    }

    /**
     *
     * Returns debug information generated by `getPaths()`, etc.
     *
     * @return array
     *
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     *
     * Sets strict mode.
     *
     * @param bool $strict Whether or not to operate in strict mode.
     *
     * @return null
     *
     */
    public function setStrict($strict)
    {
        $this->strict = (bool) $strict;
    }

    /**
     *
     * Is the includer in strict mode?
     *
     * @return bool True for strict mode, false for not.
     *
     */
    public function isStrict()
    {
        return $this->strict;
    }

    /**
     *
     * Sets the directories to traverse through; clears all previous
     * directories.
     *
     * @param array $dirs The directories to traverse through.
     *
     * @return null
     *
     */
   public function setDirs(array $dirs)
   {
       $this->dirs = array();
       $this->addDirs($dirs);
   }

    /**
     *
     * Adds directories to traverse through; appends to the existing
     * directories.
     *
     * @param array $dirs The directories to traverse through.
     *
     * @return null
     *
     */
    public function addDirs(array $dirs)
    {
        foreach ($dirs as $dir) {
            $this->addDir($dir);
        }
    }

    /**
     *
     * Adds one directory to traverse through.
     *
     * @param string $dir The directory to traverse through.
     *
     * @return null
     *
     */
    public function addDir($dir)
    {
        $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
        $dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->dirs[] = $dir;

        // reset debug information
        $this->debug = array();
    }

    /**
     *
     * Returns the directories to be traversed through.
     *
     * @return array The directories to be traversed through.
     *
     */
    public function getDirs()
    {
        return $this->dirs;
    }

    /**
     *
     * Sets the files to look for in the directories; clears all previous
     * files.
     *
     * @param array $files The files to look for.
     *
     * @return null
     *
     */
    public function setFiles(array $files)
    {
        $this->files = array();
        $this->addFiles($files);
    }

    /**
     *
     * Adds files to to look for in the directories; appends to the existing
     * files.
     *
     * @param array $files The files to look for.
     *
     * @return null
     *
     */
    public function addFiles(array $files)
    {
        foreach ($files as $file) {
            $this->addFile($file);
        }
    }

    /**
     *
     * Adds one file to look for in the directories.
     *
     * @param string $file The file to look for.
     *
     * @return null
     *
     */
    public function addFile($file)
    {
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
        $this->files[] = $file;

        // reset debug information
        $this->debug = array();
    }

    /**
     *
     * Returns the files to look for in the directories.
     *
     * @return array The files to look for in the directories.
     *
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     *
     * Sets the path to the cache file, if any.
     *
     * @param string $cache_file The path to the cache file.
     *
     * @return null
     *
     */
    public function setCacheFile($cache_file)
    {
        $this->cache_file = $cache_file;
    }

    /**
     *
     * Returns the path to the cache file.
     *
     * @return string The path to the cache file.
     *
     */
    public function getCacheFile()
    {
        return $this->cache_file;
    }

    /**
     *
     * merge the variables to extract inside the limited include scope.
     *
     * @param array $vars The variables to extract inside the limited include
     * scope.
     *
     * @return null
     *
     * @see extract()
     *
     */
    public function addVars(array $vars)
    {
        $this->vars = array_merge($this->vars, $vars);
    }

    /**
     *
     * Sets the variables to extract inside the limited include scope.
     *
     * @param array $vars The variables to extract inside the limited include
     * scope.
     *
     * @return null
     *
     * @see extract()
     *
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     *
     * Returns the variables to extract inside the limited include scope.
     *
     * @return array The variables to extract inside the limited include
     * scope.
     *
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     *
     * Resets the old valkue or set the variable to extract inside the
     * limited include scope.
     *
     * @param string $key The variable to extract inside the limited include
     * scope.
     *
     * @param mixed $value The value of the variable to extract inside
     * the limited include scope.
     *
     * @return null
     *
     * @see extract()
     *
     */
    public function setVar($key, $value)
    {
        $this->vars[$key] = $value;
    }

    /**
     *
     * Gets the list of paths to look for, combined from the directories and
     * files; returns only paths that exist and are readable.
     *
     * @param string $order Combine the paths in this order; self::DIR_ORDER
     * to look for all files in each directory first, or self::FILE_ORDER to
     * look through all directories for each file first.
     *
     * @return array The readable paths combined from the directories and
     * files.
     *
     */
    public function getPaths($order = self::DIR_ORDER)
    {
        $this->debug = array(
            "Order: $order",
            "Strict: " . ($this->isStrict() ? 'true' : 'false'),
        );

        if ($order == self::DIR_ORDER) {
            return $this->getPathsByDirOrder();
        }

        if ($order == self::FILE_ORDER) {
            return $this->getPathsByFileOrder();
        }

        throw new Exception\NoSuchOrder;
    }

    /**
     *
     * Returns the paths in directory-first order.
     *
     * @return array
     *
     */
    protected function getPathsByDirOrder()
    {
        echo 'hghg';

        var_dump($this->dirs);

        $this->paths = array();
        foreach ($this->dirs as $dir) {
            foreach ($this->files as $file) {
                $this->addGlob($dir, $file);
            }
        }
        var_dump($this->paths);

        return $this->paths;
    }

    /**
     *
     * Returns the paths in file-first order.
     *
     * @return array
     *
     */
    protected function getPathsByFileOrder()
    {
        $this->paths = array();
        foreach ($this->files as $file) {
            foreach ($this->dirs as $dir) {
                $this->addGlob($dir, $file);
            }
        }
        return $this->paths;
    }

    /**
     *
     * Given a directory and a file spec, globs them for file paths, and adds
     * them if they are found.
     *
     * @param string $dir The directory to glob.
     *
     * @param string $file The file to glob.
     *
     * @return null
     *
     */
    protected function addGlob($dir, $file)
    {
        $glob = glob($dir . $file);

        // error or no results?
        if (! $glob) {
            return;
        }

        foreach ($glob as $path) {

            // strict?
            if (! $this->strict) {
                $this->paths[] = $path;
                $this->debug[] = "Found: $path";
                continue;
            }

            // convert the path to its real location so that directory checks
            // work properly, even with directory traversal.
            $path = realpath($path);

            // is the file actually in the specified directory?
            // this will fail with symlinks.
            $dir_len = strlen($dir);
            if (substr($path, 0, $dir_len) != $dir) {
                // not actually in the directory, don't retain it
                $this->debug[] = "Not found (directory): $path";
                continue;
            }

            // retain the real path
            $this->paths[] = $path;
            $this->debug[] = "Found: $path";
        }
    }

    /**
     *
     * Include the paths combined from the directories and files.
     *
     * @param string $order Combine the paths in this order; self::DIR_ORDER
     * to look for all files in each directory first, or self::FILE_ORDER to
     * look through all directories for each file first.
     *
     * @return null
     *
     */
    public function load($order = self::DIR_ORDER)
    {
        $limited_include = $this->limited_include;

        if ($this->cache_file && is_readable($this->cache_file)) {
            echo 'cache found!';
            $this->debug = array("Load: {$this->cache_file}");
            $limited_include($this->cache_file, $this->vars);
            return;
        }

        echo 'no cache found. scanning dirs!';

        $paths = $this->getPaths($order);

        var_dump($paths);

        foreach ($paths as $path) {
            echo '<br> checking: ' . $path;
            $this->debug[] = "Load: $path";
            $limited_include($path, $this->vars);
        }
    }

    /**
     *
     * Concatenate the contents of the paths combined from the directories and
     * files; strip opening and closing PHP tags, replace __FILE__ with the
     * appropriate file name string, and __DIR__ with the appropriate
     * directory name string, and add comments indicate the original path
     * locations.
     *
     * @param string $order Combine the paths in this order; self::DIR_ORDER
     * to look for all files in each directory first, or self::FILE_ORDER to
     * look through all directories for each file first.
     *
     * @return string The contents of the concatenated paths.
     *
     */
    public function read($order = self::DIR_ORDER)
    {
        $text = '';
        $paths = $this->getPaths($order);
        foreach ($paths as $path) {
            $this->debug[] = "Read: $path";
            $text .= $this->readFileContents($path);
        }
        return $text;
    }

    /**
     *
     * Gets the contents of a file path and modifies it for concatenation.
     *
     * @param string $path The file to get the contents of.
     *
     * @return string The contents modified for concatenation.
     *
     */
    protected function readFileContents($path)
    {
        // get the file contents
        $text = file_get_contents($path);

        // trim all whitespace
        $text = trim($text);

        // strip any leading php tag
        if (substr($text, 0, 5) == '<?php') {
            $text = substr($text, 5);
        }

        // strip any trailing php tag
        if (substr($text, -2) == '?>') {
            $text = substr($text, 0, -2);
        }

        // replace __FILE__ constant with actual file name string. this is
        // because we are concatenating from multiple files. probably better
        // to do token replacement rather than string replacement.
        $text = str_replace('__FILE__', "'$path'", $text);

        // replace__DIR__ constant with actual directory name string. this is
        // because we are concatenating from multiple files. probably better
        // to do token replacement rather than string replacement.
        $text = str_replace('__DIR__', "'" . dirname($path) . "'", $text);

        // return with a leading comment and trailing newlines
        return '/**' . PHP_EOL
              . ' * ' . $path . PHP_EOL
              . ' */' . PHP_EOL
              . trim($text) . PHP_EOL . PHP_EOL;
    }
}
