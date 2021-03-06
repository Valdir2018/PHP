<?php


namespace Livro\Core;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Exception;

class AppLoader 
{
	protected $directories;
    /**
    * Add um diretorio a ser vasculhado
    */
	public function addDirectory($directory) 
	{
		$this->directories[] = $directory;
	}
	/*
	* Registra o AppLoader
	*/
	public function register() 
	{
		spl_autoload_register(array($this, 'loadClass'));
	}
	/**
	* Carrega uma classe
	*/

	public function loadClass($class) 
	{
       $folders = $this->directories;
       foreach($folders as $folder) 
       {
       	   if(file_exists("{$folder}/{$class}.php")) 
       	   {
              require_once "{$folder}/{$class}.php";
              return TRUE;
       	   }
       	   else 
       	   {  /* Verifica se o arquivo existe  */ 
       	   	  if(file_exists($folder)) 
       	   	  {
                  foreach( new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder),
                                                            RecursiveIteratorIterator::SELF_FIRST) as $entry) 
                  {
                      if(is_dir($entry)) // verifica se é um directory 
                      {
                         if(file_exists("{$entry}/{$class}.php")) 
                         {
                            require_once "{$entry}/{$class}.php";
                            return TRUE;
                         } 
                      } 
                  }
       	   	  }
       	   }
       }
	}
}