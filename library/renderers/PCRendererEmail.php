<?
/**
 * Description of PCRendererEmail
 *
 * @author paolo
 */
class PCRendererEmail extends PCRenderer {
    
    

    /**
     *
     * @var string
     */
    private $file_path;

    
    public function __construct($result, $file_name) {
        parent::__construct($result);
        $template_name = 'fresh';
        $this->file_path = __ROOT__ . "/templates/"  . $template_name . "/email/" . $file_name.".php";
    }

    /**
     * 
     * @return string
     */
    public function render() {
        ob_start();
        require $this->file_path;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }   
}

