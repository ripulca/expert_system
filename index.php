<?php 
$strs=array();
$terms=array();
$questions=array();
$read = fopen('база_знаний.txt', 'r');
while(!feof($read)){
    $str=trim(fgets($read), " \n\r");
    $results=explode("ТО", $str)[1];
    array_push($strs, $str);
    preg_match_all('/\(.*?\)/', $str, $tmp);
    foreach($tmp[0] as &$el){
        $el=trim($el, "()");
        $key_ans=explode('=',$el);
        if(!array_key_exists($key_ans[0], $questions)){
            $questions[$key_ans[0]]=array();
            array_push($questions[$key_ans[0]],$key_ans[1]);
        }
        else if(!in_array($key_ans[1], $questions[$key_ans[0]])){
            array_push($questions[$key_ans[0]], $key_ans[1]);
        }
    }
    array_push($tmp, $results);
    array_push($terms, $tmp);
}

function echo_smth($iter, $question, $answers){
    // echo "<form name='question_form' class='form' method='POST'>";
    echo "<label>".$question."</label> ";
    echo "<select name='question".$iter."'>";
    echo "<option>Не выбрано</option>";
    foreach($answers as $answer){
        echo "<option>".$answer."</option>";
    }
    echo "</select><br><br>";
    // echo "<button>Отправить</button>
    // </form><br><br>";
}
// var_dump($questions);
echo "<!DOCTYPE html><html lang='en'>";
echo "<form name='question_form' class='form' method='POST'>";
$count=0;
foreach($questions as $question=>$answers){
    echo_smth($count, $question,$answers);
    $count++;
}
$keys=array_keys($questions);
echo "<button>Отправить</button>
    </form><br><br>";
$answers=array();
// var_dump($keys);
for($i=0;$i<$count;$i++){
    if(isset($_POST['question'.$i])){
        // $answers[$keys[$i]]=array();
        // var_dump($keys[$i]);
        $answers[$keys[$i]]=trim($keys[$i], " ")."=".$_POST['question'.$i];
    }
}
// var_dump($answers);
$result=array(); $match=true;
foreach($terms as $term){
    foreach($term[0] as $el){
        // var_dump($el); echo "<br><br><br>";
        if(!in_array($el, $answers)){
            $match=false;
            break;
        }
    }
    if($match==true){
        if(in_array(trim(explode("=",$term[1])[0], " "), $keys, true)){
            $answers[trim(explode("=",$term[1])[0], " ")]=trim($term[1], " ");
        }
            array_push($result, $term[1]);
    }
    $match=true;
    // var_dump($answers); echo "<br><br><br>";
}
var_dump($result);