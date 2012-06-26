<?php
class RecursiveClassLoder
{
  /**
   * @var string
   */
  protected $directoryToBeLoaded;

  /**
   * @var string
   */
  protected $projectRoot;

  /**
   * @param string $projectRoot
   * @param string $directoryToBeLoaded
   */
  public function __construct($projectRoot, $directoryToBeLoaded)
  {
    $this->directoryToBeLoaded = $directoryToBeLoaded;
    $this->projectRoot         = $projectRoot;
  }

  /**
   * @param string $className Name of the class, will be invoked by de SPL autoloader.
   */
  public function load($className)
  {
    static $classes;

    if ($classes === null) {

       $regexIterator = new RegexIterator(
          new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
              $this->directoryToBeLoaded
            )
          ),
          '/^.+\.php$/i',
          RecursiveRegexIterator::GET_MATCH
       );

       foreach (iterator_to_array($regexIterator, false) as $file) {

          $path = current($file);
          $name = explode('/', $path);
          $name = str_replace('.php', '', end($name));

          $classes[$name] = '/'.$path;
       }
     }

     if (isset($classes[$className])) {
       require $this->projectRoot . $classes[$className];
     }
  }
}
