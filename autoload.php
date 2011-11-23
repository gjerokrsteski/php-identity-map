<?php
set_include_path(
    dirname(dirname(__FILE__))
    .'/php-identity-map'
    .PATH_SEPARATOR
    .dirname(__FILE__)
    .PATH_SEPARATOR
    .get_include_path()
);

class RecursiveClassLoder
{
  protected $directory;

  public function __construct($directory)
  {
    $this->directory = $directory;
  }

  public function autoload($className)
  {
    static $classes = null;

    if ($classes === null)
    {
       $regexIterator = new RegexIterator(
          new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->directory)
          ),
          '/^.+\.php$/i',
          RecursiveRegexIterator::GET_MATCH
       );

       foreach (iterator_to_array($regexIterator, false) as $file)
       {
          $path = current($file);
          $name = explode('/', $path);
          $name = str_replace('.php', '', end($name));

          $classes[$name] = '/'.$path;
       }
     }

     if (isset($classes[$className]))
     {
       require __DIR__ . $classes[$className];
     }
  }
}

spl_autoload_register(array(new RecursiveClassLoder('src/'), 'autoload'));
