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
