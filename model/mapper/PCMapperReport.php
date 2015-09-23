<?
/**
 * Description of PCMapperReport
 *
 * @author paolo
 */
class PCMapperReport extends PCMapper{
    
    public function getMappedInstance($attributes) {
        $user_identifier = $attributes['user_identifier'];
        $user_identifier = $attributes['site_identifier'];
        $text = $attributes['text'];
        $date_added = $attributes['date_added'];
        return new PCModelReport($user_identifier, $site_identifier, $text, $date_added);
    }

    public function getRequiredAttributes() {
        return array("identifier","user_identifier","review_identifier","text","date_added");
    }

    public function getTableName() {
        return 'reports_tbl';
    }    
    
    /**
     * 
     * @param string $userId
     * @param string $reviewId
     * @param string $text
     * @return bool
     */
    public static function createReport($userId, $reviewId, $text){
        $dateAdded = new DateTime('now', new DateTimeZone('UTC'));
        $values = array(
            'user_identifier'=> $userId,
            "review_identifier"=>$reviewId,
            'text'=>$text , 
            "date_added"=>$dateAdded->format('Y-m-d H:i:s')
                );
        return PCModelManager::insertObject(PCModelReport::getMapper(), $values);
    }
}

