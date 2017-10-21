<?php

//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);


//instantiate the program object

//Class to load classes it finds the file when the progrm starts to fail for calling a missing class
class Manage {
    public static function autoload($class) {
        //you can put any file name or directory here
        include $class . '.php';
    }
}

spl_autoload_register(array('Manage', 'autoload'));

//instantiate the program object
$obj = new main();


class main {

    public function __construct()
    {
        //print_r($_REQUEST);
        //set default page request when no parameters are in URL
        $pageRequest = 'uploadform';
        //check if there are parameters
        if(isset($_REQUEST['page'])) {
            //load the type of page the request wants into page request
            $pageRequest = $_REQUEST['page'];
        }
        //instantiate the class that is being requested
         $page = new $pageRequest;


        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $page->get();
        } else {
            $page->post();
        }

    }

}

abstract class page {
    protected $html;

    public function __construct()
    {
        $this->html .= '<html>';
       
        $this->html .= '<body>';
    }
    public function __destruct()
    {
        $this->html .= '</body></html>';
        print_r($this->html);
       
    }

    
}



class uploadform extends page
{

    public function get()
    {
        $form = '<form action="index.php?page=uploadform" method="post"
  enctype="multipart/form-data">';
        $form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
        $form .= '<input type="submit" value="Upload csv file" name="submit">';
        $form .= '</form> ';
        $this->html .= '<h1>Upload Form</h1>';
        $this->html .= $form;

    }

    public  function post() {

        //path for file to be upload
     $target_dir = "/afs/cad/u/s/j/sjp77/public_html/project1/uploads/";
                    
     
     $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
     
     //using move function to upload file to a new loaction
     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
         {
            echo $target_file;
            //using header for sending data to particular loaction
            header('Location:https://web.njit.edu/~sjp77/project1/index.php?page=htmlTable&filename='.$target_file);
          }
    else
        {
        echo "Sorry, there was an error uploading your file.";
        }
    }
}


//class for diaplaying table
class htmlTable extends page {

    public function get()
 
      {

        
        //opens the file specified
        
$my= fopen($_GET['filename'],"r") or die("unable to open");

echo "<html><body><table border=3>\n\n";
//echo "<table border="3">";
//fgetcsv to convert the csv file into array and display it
while (($m = fgetcsv($my)) !== false) 
  {
        
        echo "<tr>";
        foreach ($m as  $n)
         {
                echo "<td>" . htmlspecialchars($n) . "</td>";
         }
        echo "</tr>\n";
  }
fclose($my);
echo "\n</table></body></html>";

      }


}

?>