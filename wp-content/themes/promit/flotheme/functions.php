<?php
    // Get related politician
    function pro_get_politician($post_id = null) {
      if(!$post_id) {

          $post_id = get_the_id();
      }

      $politician_id = get_post_meta($post_id, 'destination', true);
      $politician = wp_get_single_post($politician_id, OBJECT);

      if($politician) {
          return $politician;
      }

      return false;
}

    // Get related party
    function pro_get_party($politician_id) {

        if(!$politician_id) {
            return false;
        }

        $party_id = get_post_meta($politician_id, 'party', true);
        $party = wp_get_single_post($party_id);

        if($party) {
            return $party;
        }

        return false;

    }

    function pro_user_voted($post_id = null) {

        $vote = new Flotheme_Plugin_Vote;

        if(!$post_id) {
            $post_id = get_the_ID();
        }

        return $vote->user_voted($post_id);
    }

    function pro_get_votes($post_id = null){
        $vote = new Flotheme_Plugin_Vote;

        if(!$post_id) {
            $post_id = get_the_ID();
        }

        return $vote->get_votes($post_id);
    }

    function pro_get_time_limit($post_id) {
        if($post_id) {
            $post_id = get_the_ID();
        }

        $result = array();

        $current_date = time();

        $promise_start = strtotime(get_post_meta($post_id, 'from', true));
        $promise_end = strtotime(get_post_meta($post_id, 'by', true));

        if( ($promise_end != '') && ($promise_start != '') ){

            $term = $promise_end - $promise_start;

            $passed = $current_date - $promise_start;

            $result['progress'] = 100-$passed/$term*100;
            $result['remained'] = intval($term/86400);

        }   else {
            return false;
        }



        return $result;
    }
