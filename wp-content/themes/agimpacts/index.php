<?php get_header(); ?>

<section id="content" class="row"> 
  <div id="home-content" class="table-ag">
    <img class="left" src="<?php echo get_template_directory_uri(); ?>/img/images/ag-home-image.png">
    <p>CCAFS and University of Leeds, with the collaboration of other institutions (CSIRO, Stanford University) are developing a platform to facilitate the investigation of climate change impacts on agriculture. The database that will appear here has been used in the food chapters of the IPCC 4th and 5th assessment reports. The data consists of peer-reviewed literature reports on yield projections from crop simulation studies.</p>
    <p>Examples of the use of the database are in Fig. 5.2 of Easterling et al. (2007) (available at <a style="text-decoration: underline;" href="http://www.ipcc.ch/pdf/assessment-report/ar4/wg2/ar4-wg2-chapter5.pdf">http://www.ipcc.ch/pdf/assessment-report/ar4/wg2/ar4-wg2-chapter5.pdf</a>), and also in Challinor et al. (2014). We envisage this portal and database will be key to assessing consensus and robustness across studies on climate change impacts on crops.</p>
    <p>Making these data available, and developing a platform for ongoing crowd-sourced development of the data set, will ultimately support adaptation efforts regionally and globally.</p>
    <!--<p style='font-style: oblique;'><strong>References</strong>-->
    
    <ul id="agref" style='font-style: oblique;'>
      <li style="list-style-type: none;"><strong>References</strong></li>
      <li>
        Easterling, W E, P K Aggarwal, P Batima, K M Brander, L Erda, S M Howden, A Kirilenko, et al. 2007. “Food, Fibre and Forest Products.” In Climate Change 2007: Impacts, Adaptation and Vulnerability. Contribution of Working Group II to the Fourth Assessment Report of the Intergovernmental Panel on Climate Change, edited by M L Parry, O F Canziani, J P Palutikof, P J van der Linden, and C E Hanson, 273–313. Cambridge, UK: Cambridge University Press.
      </li>
      <li>
        Challinor, A.J., Watson, J., Lobell, D.L., Howden, S.M., Smith, D.R., and Chhetri, N. 2014. A meta-analysis of crop yield under climate change and adaptation. Nature Climate Change 4, 287–291, <a style='text-decoration: underline; ' href='http://www.nature.com/nclimate/journal/vaop/ncurrent/full/nclimate2153.html'>doi:10.1038/nclimate2153</a>
      </li>
    </ul>
    <!--</p>-->
  </div>
  <div class="sidebar-ag table-ag">
    <?php dynamic_sidebar('analitics'); ?>
  </div>
</section>


<?php get_footer(); ?>