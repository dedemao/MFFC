<?php
namespace App\controllers;
use App\models\Article;

/**
* \HomeController
*/
class HomeController extends BaseController
{
  
  public function home()
  {
		Article::first();
		//echo "<h1>控制器成功！</h1>";
  }
}