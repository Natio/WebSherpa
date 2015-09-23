
<style>
    #downloadsContainer{
        margin-left: auto;
        margin-right: auto;
        width: 500px;
       
        /*min-height: 600px;*/
    }
    .downloadGroupContainer{
        margin-bottom: 5px;
    }
    p.downloadGroupTitle{
        font-size: 24px;
        text-align: center;
        color: orangered;
        font-weight: bolder;
    }
    
    .downloadItemTitleContainer{
       
        margin-top: 0px;
    }
    .downloadItemTitleContainer p{
        display: inline;
       
    }
    .downloadItemTitleContainer a{
        
        font-size: 18px;
    }
    .downloadItemContainer .downloadItemCommentContainer p{
        margin-left: 30px;
        line-height: 14px;
        font-size: 14px;
    }
    
</style>

<div id="downloadsContainer">
    
    <?
    
    $groups = $this->result['groups'];
    
    $length = count($groups);
    
    for($i = 0; $i < $length ; $i++){
        $group = $groups[$i];
        echo "<div class=\"downloadGroupContainer\">";
            echo "<p class=\"downloadGroupTitle\">";
            echo $group['name'];
            echo "</p>";
        
            printDlItems($group['links']);
        
        
       echo "</div>";
    }
    
    function printDlItems($items){
         $length = count($items);
         for($i = 0; $i < $length ; $i++){
             $item = $items[$i];
             echo "<div class=\"downloadItemContainer\">";
                echo "<div class=\"downloadItemTitleContainer\">";
                    echo "<p>";
                    echo $item['name'];
                    echo "</p>";
                    echo "<a href=\"".$item['link']."\">";
                    echo " Download";
                    echo "</a>";
                    echo "<p> (";
                    echo $item['date_added'];
                    echo ")</p>";
                echo "</div>";
                echo "<div class=\"downloadItemCommentContainer\">";
                    echo "<p>";
                    echo $item['comment'];
                    echo "</p>";
                 echo "</div>"; 
             echo "</div>"; 
         }
    }
    
    ?> 
</div>