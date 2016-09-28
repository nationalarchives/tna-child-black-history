<?php
/*
Template Name: Black History holding
*/
get_header(); ?>
<div class="black-history">
	<div class="banner" role="banner">
		<?php get_template_part( 'breadcrumb' ); ?>
		<div class="heading-banner text-center">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h1>Black British History</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="menu">
		<div class="container">
			<div class="row">
				&nbsp;
			</div>
		</div>
	</div>
	<main id="main" class="content-area holding-page" role="main">
		<div class="container">
			<div class="row">
					<div class="col-md-12">
						<?php while ( have_posts() ) : the_post(); ?>
							<article>
								<div class="entry-content clearfix">
									<?php the_content(); ?>
								</div>
							</article>
						<?php endwhile; ?>
					</div>
			</div>
		</div>
	</main>
</div>
<?php get_footer(); ?>
