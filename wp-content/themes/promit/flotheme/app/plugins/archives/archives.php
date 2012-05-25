<?php
class Flotheme_Plugin_Archives
{
    public function __construct() {
        
    }
    
    public function init()
    {
        
    }
    
    /**
     * Get archives by year
     * 
     * @global object $wpdb
     * @param int $year
     * @return array 
     */
    public function getByYear($year = "") {
        global $wpdb;

        $where = "";
        if (!empty($year)) {
            $where = "AND YEAR(post_date) = " . ((int) $year);
        }
        $query = "SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, DATE_FORMAT(post_date, '%b') AS `abmonth`, DATE_FORMAT(post_date, '%M') AS `fmonth`, count(ID) as posts
                                        FROM $wpdb->posts
                                WHERE post_type = 'post' AND post_status = 'publish' $where
                                        GROUP BY YEAR(post_date), MONTH(post_date)
                                        ORDER BY post_date DESC";

        return $wpdb->get_results($query);
    }

    /**
     * Get years for archives
     * 
     * @global object $wpdb
     * @return type 
     */
    public function getYears() {
        global $wpdb;

        $query = "SELECT DISTINCT YEAR(post_date) AS `year`
                                        FROM $wpdb->posts
                                WHERE post_type = 'post' AND post_status = 'publish'
                                        GROUP BY YEAR(post_date) ORDER BY post_date DESC";

        return $wpdb->get_results($query);
    }

    /**
     * Get months list
     * 
     * @return array
     */
    function getMonths() {
        return array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    }
}

function flotheme_archives()
{
    $obj = new Flotheme_Plugin_Archives();
    $year = null;
    ?>
    <div class="archives">
        <?php
            $months = $obj->getMonths();
            $archives = $obj->getByYear();
        ?>
        <div class="year">
            
            <span id="archives-active-year"></span>
            <a href="#" class="up">up</a>
            <a href="#" class="down">down</a>
        </div>
        <div class="months">
            <?php foreach ($archives as $archive) : ?>
                <?php
                    if ($year == $archive->year) {
                        continue;
                    }
                    $year = $archive->year;
                    $y_archives = $obj->getByYear($archive->year);
                ?>
                <div class="year-months" id="archive-year-<?php echo $year?>">
                <?php foreach ($months as $key => $month) :?>
                    <?php foreach ($y_archives as $y_archive) :?>
                        <?php if (($key == ($y_archive->month-1)) && $y_archive->posts):?>
                            <a href="<?php echo get_month_link($year, $y_archive->month)?>"><?php echo $month ?></a>
                            <?php if ($key != 11):?>
                                <span class="delim">&nbsp;/&nbsp;</span>
                            <?php endif;?>
                            <?php break;?>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php if ($key != $y_archive->month-1):?>
                        <span><?php echo $month; ?></span>
                        <?php if ($key != 11):?>
                            <span class="delim">&nbsp;/&nbsp;</span>
                        <?php endif;?>
                    <?php endif;?>
                <?php endforeach;?>
                </div>
            <?php endforeach;?>
        </div>
    </div>
<?php
}