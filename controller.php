<?php session_start();
 header('Content-type: text/html; charset=utf-8');
 setlocale(LC_ALL, 'ru_RU.UTF-8');     
?>
<?php
/**
* Исключения из парсинга
*/
class ParsingException
{
    public $art = null;
    
    function __construct($art)
    {
        $this->art = $art;
    }
    public static function all() {
        $db = connect();
        
        if($result = $db->query("SELECT product_code FROM parsing_exceptions ORDER BY product_code")) {
            $rows = array();
            while($row = $result->fetch_row()) {
              $rows[]=$row;
            }
            $result->close();
            return $rows;
        }
        $db->close();
        return false;
    }
    public static function find($product_code) {
        $db = connect();
        $row = getRow($db, "SELECT product_code FROM parsing_exceptions WHERE product_code LIKE '".$product_code."'");
        if($row) {
            return true;
        }
        else {
            $db->close();
            return false;
        }
    }
}

/**
* Product model
*/
class Product
{
    public $id = null;
    public $art = null;
    public $name = null;
    public $unit = null;
    public $price = null;
    public $free = null;
    public $reserved = null;
    public $delivery_free = null;
    public $delivery_reserved = null;   
    public $delivery_date = null;   
    
    
    function __construct($id, $art, $name, $unit, $price, $free, $reserved, $delivery_free, $delivery_reserved, $delivery_date)
    {
        $this->id = $id;
        $this->art = $art;
        $this->name = $name;
        $this->unit = $unit;
        $this->price = $price;
        $this->free = $free;
        $this->reserved = $reserved;
        $this->delivery_free = $delivery_free;
        $this->delivery_reserved = $delivery_reserved;   
        $this->delivery_date = $delivery_date;   
        
    }
    public function processing_product()
    {
        $db = connect();
        if(ParsingException::find($this->art)) {
            $db->close();
            return false;
        }
        
        $product = getRow($db, "SELECT product_code FROM SC_products WHERE product_code LIKE '".$this->art."' LIMIT 1");
        if($product) {
            if($stmt = $db->prepare("UPDATE SC_products SET name_ru = ?, 
                     Price = ?, 
                     shipping_freight = ? WHERE product_code LIKE ?")) {
                         
                $stmt->bind_param('sdds',$this->name ,$this->price ,$this->delivery_reserved ,$this->art);
                if($stmt->execute()) {
                    $stmt->close(); 
                    return 1;
                }
                else {
                    $stmt->close(); 
                    return false;
                }
            }
            else {
                echo "error".$db->error;
            }
        }
        else {
            //insert new product
            if($stmt = $db->prepare("INSERT INTO SC_products(name_ru, 
                     Price, 
                     shipping_freight, product_code) VALUES (?,?,?,?)")) {
                         
                $stmt->bind_param('sdds',$this->name ,$this->price ,$this->delivery_reserved ,$this->art);
                if($stmt->execute()) {
                    $newId = $stmt->insert_id;
                    $stmt->close(); 
                    return 2;
                }
                else {
                    $stmt->close(); 
                    return false;
                }
            }
            else {
                echo "error".$db->error;
            }    
        }
    
    }
}

?>
<?php
//Authorization
if(isset($_SESSION['parser_session'])) {
    // echo "set";
}

function curl_get_file($URL) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents) return $contents;
    else return $err;
}
function connect()
{
    $db = new mysqli("localhost", "root", "", 'giftsspb_webasyst');
    if(!$db) { die(mysql_error()); }
    else { return $db; }
}
function getRow($conn,$sql) {
    if ( $rs = $conn->query($sql) ) {
        $r = $rs->fetch_array();
        $rs->free();
        return $r;
    }
    return null;
}
//Insert into db or update product
function processing_product()
{
    $db = connect();
    $product = getRow($db, "SELECT product_code FROM SC_products WHERE product_code LIKE '".$p['articul']."' LIMIT 1");
    if($product) {
        if($stmt = $db->prepare("UPDATE SC_products SET name_ru = ?, 
                 Price = ?, 
                 shipping_freight = ? WHERE product_code LIKE ?")) {
                         
            $stmt->bind_param('sdds',$p['name'] ,$p['price'] ,$p['delivery_reserved'] ,$p['articul']);
            if($stmt->execute()) {
                $stmt->close(); 
                return 1;
            }
            else {
                $stmt->close(); 
                return false;
            }
        }
        else {
            echo "error".$db->error;
        }
    }
    else {
        //insert new product
        if($stmt = $db->prepare("INSERT INTO SC_products(name_ru, 
                 Price, 
                 shipping_freight, product_code) VALUES (?,?,?,?)")) {
                         
            $stmt->bind_param('sdds',$p['name'] ,$p['price'] ,$p['delivery_reserved'] ,$p['articul']);
            if($stmt->execute()) {
                $newId = $stmt->insert_id;
                $stmt->close(); 
                return 2;
            }
            else {
                $stmt->close(); 
                return false;
            }
        }
        else {
            echo "error".$db->error;
        }    
    }
    
}
//load content of catalog
function parse_catalog() {
    // $catalogue = curl_get_file("ftp://clients:cLiENts2010@195.2.87.149/catalogue.xml");
    $catalogue = file_get_contents("catalogue.xml");
    $updated_count = 0;
    $inserted_count = 0;
    if($catalogue) {
        $xml_catalogue = new SimpleXMLElement($catalogue);  
        $products = array(); 
        foreach($xml_catalogue->Товары->Товар as $xp) {
            $product = new Product($xp->ИД, $xp->Артикул, $xp->Наименование, $xp->ЕдиницаИзмерения, $xp->ЦенаРуб, $xp->Свободный, $xp->Занятый, $xp->Поставка->СвободныйВПути, $xp->Поставка->ЗанятыйВПути, $xp->Поставка->ДатаПоставки);
            $product_arr['id'] = $xp->ИД;
            $product_arr['articul'] = $xp->Артикул;
            $product_arr['name'] = $xp->Наименование;
            $product_arr['unit'] = $xp->ЕдиницаИзмерения;
            $product_arr['price'] = $xp->ЦенаРуб;
            $product_arr['reserved'] = $xp->Занятый;
            $product_arr['delivery_free'] = $xp->СвободныйВПути;
            $product_arr['delivery_reserved'] = $xp->ЗанятыйВПути;
            $product_arr['delivery_date'] = $xp->ДатаПоставки;
            
            array_push($products, $product_arr);
            if($processing_status = $product->processing_product()) {
                if($processing_status == 1) $updated_count += 1;
                if($processing_status == 2) $inserted_count += 1;
            }
        }
        
        // $arr_replace_utf = array('\u0410', '\u0430','\u0411','\u0431','\u0412','\u0432',
        // 
        // '\u0413','\u0433','\u0414','\u0434','\u0415','\u0435','\u0401','\u0451','\u0416',
        // 
        // '\u0436','\u0417','\u0437','\u0418','\u0438','\u0419','\u0439','\u041a','\u043a',
        // 
        // '\u041b','\u043b','\u041c','\u043c','\u041d','\u043d','\u041e','\u043e','\u041f',
        // 
        // '\u043f','\u0420','\u0440','\u0421','\u0441','\u0422','\u0442','\u0423','\u0443',
        // 
        // '\u0424','\u0444','\u0425','\u0445','\u0426','\u0446','\u0427','\u0447','\u0428',
        // 
        // '\u0448','\u0429','\u0449','\u042a','\u044a','\u042b','\u044b','\u042c','\u044c',
        // 
        // '\u042d','\u044d','\u042e','\u044e','\u042f','\u044f');
        // 
        // $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е',
        // 
        // 'Ё', 'ё', 'Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н','О','о',
        // 
        // 'П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ч','ч','Ш','ш',
        // 
        // 'Щ','щ','Ъ','ъ','Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я');
        // 
        // $output = str_replace($arr_replace_utf,$arr_replace_cyr,$products);
        $output = array('result' => 'true', 'total_count'=>count($products), 'changed_count'=>$updated_count, 'inserted_count'=>$inserted_count);
        // header('Content-Type: application/x-javascript; charset=utf8');  
        echo json_encode($output);
        unset($products);
        unset($product_arr);
        unset($$xml_catalogue);
        unset($catalogue);
        exit;
        // echo var_dump($xml_catalogue->Товары->Товар[0]);
    }
    else {
        echo "file not load";
    }
}

function list_parsing_exceptions()
{
    $pexs = ParsingException::all();
    echo json_encode($pexs);
}

//Router
switch($_REQUEST['action']) {
    case 'parse':
        parse_catalog();
    break;
    case 'parsing_exceptions':
        list_parsing_exceptions();
    break;
}
