<?php

/**
 * Template Name: Article Detail
 * @package WordPress
 * @subpackage AMKNToolbox
 */
get_header();
global $wpdb;
if ($_GET['article']) {
  $tablename = $wpdb->prefix . 'article';
  $myarticle = $wpdb->get_row("SELECT * FROM $tablename WHERE ID = " . $_GET['article'],ARRAY_A);
}
$current_user = wp_get_current_user();
//print_r($current_user);
?>
<div id="loading"><img style="" src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="Loader" /></div>
<section id="content" class="row"> 
  <div id="home-content">
    <h1>Reference Article</h1>
    <form id="articleForm" class="pure-form pure-form-aligned">
      <fieldset>
        <div class="pure-control-group">
          <label for="name">DOI</label>
          <input id="doi" name="doi" type="text" class="pure-input-1-3" placeholder="DOI" value="<?php echo (isset($myarticle['doi_article'])) ? $myarticle['doi_article'] : ''; ?>">
        </div>
        <div class="pure-controls">
            <button type="button" class="pure-button pure-button-primary" onclick="validDoi($('#doi').val())">Get metadata</button>
        </div>
        <hr>
        <div class="pure-control-group">
          <label for="title">Title</label>
          <input id="title" name="title" type="text" class="pure-input-1-3" placeholder="Title" value="<?php echo (isset($myarticle['paper_title'])) ? $myarticle['paper_title'] : ''; ?>">
        </div>
<!--        <div class="pure-control-group">
          <label for="author">Author(s)</label>
          <input id="author" name="author" type="text" class="pure-input-1-3" placeholder="Author" value="<?php // echo (isset($myarticle['author'])) ? $myarticle['author'] : ''; ?>">
        </div>-->
        <div class="pure-control-group">
          <label for="year">Year</label>
          <input id="year" name="year" type="text" class="pure-input-1-3" placeholder="Year" value="<?php echo (isset($myarticle['year'])) ? $myarticle['year'] : ''; ?>">
        </div>
        <div class="pure-control-group">
          <label for="journal">Journal</label>
          <input id="journal" name="journal" type="text" class="pure-input-1-3" placeholder="Journal" value="<?php echo (isset($myarticle['journal'])) ? $myarticle['journal'] : ''; ?>">
        </div>
        <div class="pure-control-group">
          <label for="volume">Volume</label>
          <input id="volume" name="volume" type="text" class="pure-input-1-3" placeholder="Volume" value="<?php echo (isset($myarticle['volume'])) ? $myarticle['volume'] : ''; ?>">
        </div>
        <div class="pure-control-group">
          <label for="issue">Issue</label>
          <input id="issue" name="issue" type="text" class="pure-input-1-3" placeholder="Issue" value="<?php echo (isset($myarticle['issue'])) ? $myarticle['issue'] : ''; ?>">
        </div>
        <div class="pure-control-group">
          <label for="pstart">Page Start</label>
          <input id="pstart" name="pstart" type="text" class="pure-input-1-5" style="border-style: none !important;width: 80px;font-size: initial;" value="<?php echo (isset($myarticle['page_start'])) ? $myarticle['page_start'] : ''; ?>">
        </div>
        <div class="pure-control-group">
          <label for="pend">Page End</label>
          <input id="pend" name="pend" type="text" class="pure-input-1-5" style="border-style: none !important;width: 80px;font-size: initial;" value="<?php echo (isset($myarticle['page_end'])) ? $myarticle['page_end'] : ''; ?>">
        </div>
        <div class="pure-control-group">
          <label for="reference">Reference</label>
          <input id="reference" name="reference" type="text" class="pure-input-1-3" placeholder="Reference" value="<?php echo (isset($myarticle['reference'])) ? $myarticle['reference'] : ''; ?>">
        </div>
        <?php if(isset($myarticle) && $myarticle): ?>
        <input id="article_id" name="article_id" type="hidden" value="<?php echo $myarticle['id']?>">
        <div class="pure-controls">
            <button type="button" class="pure-button pure-button-primary" onclick="saveArticle($('#articleForm').serialize())">Update</button>
            <button type="button" class="pure-button pure-button-primary" onclick="$(location).attr('href', templateUrl + '/estimate?article=<?php echo $myarticle['id'] ?>');">Add estimate</button>
            <button type="button" class="pure-button pure-button-primary" onclick="$(location).attr('href',templateUrl+'/article');">Back</button>
        </div>
        <?php else:?>
        <div class="pure-controls">
            <button type="button" class="pure-button pure-button-primary" onclick="saveArticle($('#articleForm').serialize())">Save</button>
            <button type="button" class="pure-button pure-button-primary" onclick="$(location).attr('href',templateUrl+'/article');">Back</button>
        </div>
        <?php endif;?>
      </fieldset>
    </form>
    
  </div>
</section>
<script>
  $( "#pstart" ).spinner();
  $( "#pend" ).spinner();
  $('#doi').on('keyup', function(e) {
    if (e.which == 13) {
//        e.preventDefault();
        validDoi($('#doi').val())
    }
});
</script>
<?php get_footer(); ?>