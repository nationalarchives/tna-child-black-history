<?php
/*
Template Name: Black History landing
*/
get_header(); ?>
<div class="black-history">
	<?php while ( have_posts() ) : the_post();
	$feature_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
	if ( $feature_img ) { ?>
	<div class="banner feature-img" role="banner" style="background: url( <?php echo make_path_relative( $feature_img[0] ) ?> ) no-repeat center center;background-size: cover;">
	<?php } else { ?>
	<div class="banner" role="banner">
	<?php } ?>
		<?php get_template_part( 'breadcrumb' ); ?>
		<div class="heading-banner text-center">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1><?php the_title(); ?></h1>
					</div>
				</div>
				<?php get_image_caption( 'top' ); ?>
			</div>
		</div>
	</div>
	<div class="menu" role="navigation">
		<div class="container">
			<div class="row">
				<nav class="col-md-12 text-center">
					<ul>
						<li>
							<a href="#blog-0">From our blog</a>
						</li>
						<li>
							<a href="#event">What's on</a>
						</li>
						<li>
							<a href="#podcast-0">Podcasts</a>
						</li>
						<?php
							$first_section_heading = get_post_meta( $post->ID, 'first_section_heading', true );
							if ( $first_section_heading ) { ?>
						<li>
							<a href="#first-content-section"><?php echo $first_section_heading ?></a>
						</li>
							<?php } ?>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	<main id="main" role="main" class="content-area">
		<div class="container">
			<?php if ( get_post()->post_content ) { ?>
			<section id="page-content">
				<div class="row">
					<div class="equal-heights equal-heights-flex-box">
						<div class="col-md-8">
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<div class="entry-content clearfix">
									<?php the_content(); ?>
								</div>
							</article>
						</div>
						<div class="col-md-4">
							<article>
								<?php
								$title = get_post_meta( $post->ID, 'research_guide_heading', true );
								$link = get_post_meta( $post->ID, 'research_guide_link', true );
								$description = get_post_meta( $post->ID, 'research_guide_description', true );
								?>
								<div class="entry-content clearfix">
									<h3>
										<?php if ( $link ) { ?>
										<a href="<?php echo $link ?>" title="<?php echo $title ?>">
											<?php echo $title ?>
										</a>
										<?php } else { echo $title; } ?>
									</h3>
									<p><?php echo $description ?></p>
								</div>
							</article>
						</div>
					</div>
				</div>
			</section>
			<?php } ?>
			<!-- 
			Dynamic Content Section.
			From RSS and Eventbrite.
			-->
			<section id="dynamic-content">
				<div class="row">
					<div class="equal-heights equal-heights-flex-box">
						<?php tna_posts_via_rss( 'http://blog.nationalarchives.gov.uk/blog/tag/black-british-history-portal/feed/', 'Blog', 3, 'blog' ) ?>
						<div class="col-sm-12 col-md-12 text-right bg-transparent">
							<ul class="more">
								<li>
									<a href="http://blog.nationalarchives.gov.uk/blog/tag/black-british-history-portal/" title="More blog posts">More blog posts</a>
								</li>
							</ul>
						</div>
						<div class="col-sm-4 col-md-4">
							<div id="event" class="card event clearfix">
								<div class="entry-thumbnail">
									<a href="http://nationalarchives.eventbrite.co.uk/" title="The National Archives events" target="_blank">
										<img src="<?php echo make_path_relative( get_stylesheet_directory_uri() ) ?>/img/event.jpg" alt="Black British History Event">
									</a>
								</div>
								<div class="entry-content">
									<div class="type">
										<small>What&prime;s on</small>
									</div>
									<h3>Black British History events</h3>
									<p>
										<i>Events programme loading.</i><br>
										If it does not appear after 10 seconds please
										<a href="http://nationalarchives.eventbrite.co.uk/" title="The National Archives events" target="_blank">click here</a>.
									</p>
								</div>
							</div>
						</div>
						<?php tna_posts_via_rss( 'http://media.nationalarchives.gov.uk/index.php/tag/black-british-history/feed/', 'Podcast', 2, 'podcast' ) ?>
						<div class="col-sm-12 col-md-12 text-right bg-transparent">
							<ul class="more">
								<li>
									<a href="http://media.nationalarchives.gov.uk/index.php/tag/black-british-history/" title="More podcasts">More podcasts</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!--
			First content section.
			Populated via custom fields (metabox function).
			See black_history_meta_boxes() in functions.php
			-->
			<?php if ( $first_section_heading ) { ?>
			<section id="first-content-section">
				<div class="row">
					<div class="col-md-12">
						<article>
							<div class="entry-header">
								<h2><?php echo $first_section_heading ?></h2>
							</div>
							<div class="entry-content clearfix">
								<?php
									for ($i = 1; $i <= 3; $i++) {
										$title = get_post_meta( $post->ID, 'sec_1_title_'.$i, true );
										$link = get_post_meta( $post->ID, 'sec_1_link_'.$i, true );
										$image = get_post_meta( $post->ID, 'sec_1_image_'.$i, true );
										$description = get_post_meta( $post->ID, 'sec_1_description_'.$i, true );
										if ( $title ) { ?>
											<div class="col-md-4">
												<div class="card">
													<div class="entry-thumbnail">
														<a href="<?php echo $link ?>" title="<?php echo $title ?>">
															<img src="<?php echo make_path_relative( $image ) ?>" alt="<?php echo $title ?>">
														</a>
													</div>
													<div class="entry-content">
														<h3>
															<a href="<?php echo $link ?>" title="<?php echo $title ?>">
																<?php echo $title ?>
															</a>
														</h3>
														<p><?php echo $description ?></p>
													</div>
												</div>
											</div>
										<?php }
									}
								?>
							</div>
						</article>
					</div>
				</div>
			</section>
			<?php } ?>
			<!--
			Second Content Section.
			Populated via custom fields (metabox function).
			See black_history_meta_boxes() in functions.php
			-->
			<?php if ( get_post_meta( $post->ID, 'second_section_display', true ) == 'enabled' ) { ?>
			<section id="second-content-section">
				<div class="row">
					<div class="equal-heights equal-heights-flex-box">
						<?php
						for ($i = 1; $i <= 3; $i++) {
							$title = get_post_meta( $post->ID, 'content_title_'.$i, true );
							$sub_title = get_post_meta( $post->ID, 'content_sub_title_'.$i, true );
							$image = get_post_meta( $post->ID, 'content_image_'.$i, true );
							$content = get_post_meta( $post->ID, 'content_'.$i, true );
							if ( $title ) { ?>
								<div class="col-xs-12 col-sm-12 col-md-4">
									<article class="card">
										<div class="entry-header">
											<h2><?php echo $title ?></h2>
										</div>
										<div class="entry-content clearfix">
											<div class="entry-thumbnail">
												<?php if ( $image ) { ?>
													<img src="<?php echo make_path_relative( $image ) ?>" class="img-responsive" alt="<?php echo $title ?>">
												<?php } else { ?>
													<img src="<?php echo make_path_relative( get_stylesheet_directory_uri() ) ?>/img/event-list.jpg" class="img-responsive" alt="<?php echo $title ?>">
												<?php } ?>
											</div>
											<h3><?php echo $sub_title ?></h3>
											<p><?php echo $content ?></p>
										</div>
									</article>
								</div>
							<?php }
						}
						?>
					</div>
			</section>
			<?php } ?>
			<section id="connect">
				<div class="connect">
					<div class="row">
						<div class="col-sm-6">
							<h2>Stay up-to-date and sign up to our newsletter</h2>
						</div>
						<div class="col-sm-4">
							<form name="signup" id="signup" class="pad-medium" action="http://r1.wiredemail.net/signup.ashx" method="post" role="form">
								<input type="hidden" name="addressbookid" value="636353"> <!-- homepage and general sign up -->
								<input type="hidden" name="userid" value="173459">
								<input type="hidden" name="ReturnURL" value="http://nationalarchives.gov.uk/news/subscribe-confirmation.htm">
								<label class="sr-only" for="email">Enter your email address to subscribe to our newsletter</label>
								<input type="email" id="email" class="email" name="Email" required="required" placeholder="Enter your email address" aria-label="Enter your email address to subscribe to our newsletter" aria-required="true">
								<input id="newsletterSignUp" type="submit" name="Submit" value="Subscribe" class="button">
							</form>
						</div>
						<div class="col-sm-2">
							<a href="http://www.facebook.com/TheNationalArchives" target="_blank" title="External website Facebook - link opens in a new window">
								<span class="sprite icon-facebook"></span>
							</a>
							<a href="https://twitter.com/UkNatArchives" target="_blank" title="External website Twitter - link opens in a new window">
								<span class="sprite icon-twitter"></span>
							</a>
						</div>
					</div>
				</div>
			</section>
		</div>
	</main>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>

