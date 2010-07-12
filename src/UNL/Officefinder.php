<?php
class UNL_Officefinder
{
    /**
     * Options for this use.
     */
    public $options = array('view'   => 'instructions',
                            'format' => 'html');

    /**
     * The results of the search
     * 
     * @var mixed
     */
    public $output;

    public $view_map = array('instructions' => 'UNL_Peoplefinder_Instructions',
                             'search'       => 'UNL_Peoplefinder_Department_Search',
                             'record'       => 'UNL_Peoplefinder_Department');
    
    public static $db_user = 'officefinder';
    
    public static $db_pass = 'officefinder';
    
    function __construct($options = array())
    {
        $this->options = $options + $this->options;
        $this->run();
    }
    
    public function determineView()
    {
        switch(true) {
            case isset($this->options['q']):
                $this->options['view'] = 'search';
                return;
            case isset($this->options['d']):
                $this->options['view'] = 'record';
                return;
        }

    }

    function run()
    {
        $this->determineView();
        if (isset($this->view_map[$this->options['view']])) {
            $this->output[] = new $this->view_map[$this->options['view']]($this->options);
        } else {
            throw new Exception('Un-registered view');
        }
    }

    /**
     * Connect to the database and return it
     * 
     * @return mysqli
     */
    public static function getDB()
    {
        $db = new mysqli('localhost', self::$db_user, self::$db_pass, 'officefinder');
        if (mysqli_connect_error()) {
            throw new Exception('Database connection error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        $db->set_charset('utf8');
        return $db;
    }
}