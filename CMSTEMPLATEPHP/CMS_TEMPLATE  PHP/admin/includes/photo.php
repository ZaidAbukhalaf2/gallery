<?php


class Photo extends Db_object {

    protected static $db_table = "photos";
    protected static $db_table_fildes = array('title','caption','description','filename','alternate_text','type','size');
    public $id;
    public $title;
    public $caption;
    public $description;
    public $filename;
    public $alternate_text;
    public $type;
    public $size;


    public $tmp_path;
    public $upload_directory = "images";
    public $errors = array();
    
    
        public function set_file($file){

            if(empty($file) || !$file || !is_array($file)){

                $this->errors[] = "Ther was no file uploaded here";
                return false;
            }

            $this->filename =  basename($file['name']);
            $this->tmp_path = $file['tmp_name'];
            $this->type     = $file['type'];
            $this->size     = $file['size'];
                }
        

            public function picture_path (){

                return $this->upload_directory.DS.$this->filename;
            }

            public function save(){

            if($this->id){
                $this->update();
            }else{

                if(!empty($this->errors)){

                    return false;
                }

                if(empty($this->filename) || empty($this->tmp_path)){
                    $this->erorrs[]="the file was file was not available";
                    return false;
                }


                $target_path =  SITE_ROOT . DS . 'admin' . DS . $this->upload_directory . DS . $this->filename;

                if(file_exists($target_path)){
                    $this->errors[]="The file {$this->filename} already exists";
                    return false;
                }
                if(move_uploaded_file($this->tmp_path,$this->filename)){

                    
                    if( $this->create()){

                        unset($this->tmp_path);
                        return true;
                    }

                }else{

                    $this->errors[] = "the file directory probably does not have permission";
                    return false;
                }

                // $this->create();
            }

        }

                public function delete_photo(){


                    if($this->delete()){

                        $target_path = SITE_ROOT.DS.'admin'.DS.$this->picture_path ();
                
                        return unlink($target_path)? true :false ;   
                        
                    }else{

                        return false;

                    }


                }
    }





?>