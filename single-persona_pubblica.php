<?php
/**
 * Persona template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Design_Comuni_Italia
 */

get_header();
?>
	<main>
		<?php
		while (have_posts()) :
			the_post();
			$user_can_view_post = dci_members_can_user_view_post(get_current_user_id(), $post->ID);

			$prefix = '_dci_persona_pubblica_';

			$foto_url = dci_get_meta('foto', $prefix, $post->ID);
			$foto_id = dci_get_meta('foto_id', $prefix, $post->ID);
			$descrizione_breve = dci_get_meta('descrizione_breve', $prefix, $post->ID);
			$competenze = dci_get_meta('competenze', $prefix, $post->ID);
			$deleghe = dci_get_meta('deleghe', $prefix, $post->ID);
			$gallery = dci_get_meta('gallery', $prefix, $post->ID);

			$data_conclusione_incarico = dci_get_meta('data_conclusione_incarico', $prefix, $post->ID);

			$incarichi = dci_get_meta('incarichi', $prefix, $post->ID);
			$incarichi = array_filter(array_map(fn($id)=>get_post($id), $incarichi ? (array)$incarichi : [] ));

			$organizzazioni = dci_get_meta('organizzazioni', $prefix, $post->ID);
			$organizzazioni = array_filter(array_map('intval', $organizzazioni ? (array)$organizzazioni : [] ));

			$responsabile_di = dci_get_meta('responsabile_di', $prefix, $post->ID);
			$responsabile_di = array_filter(array_map(fn($id)=>get_post($id), $responsabile_di ? (array)$responsabile_di : [] ));

			$punti_contatto = dci_get_meta('punti_contatto', $prefix, $post->ID);
			$punti_contatto = array_filter(array_map(fn($id)=>get_post($id), $punti_contatto ? (array)$punti_contatto : [] ));

			$curriculum_vitae = dci_get_meta('curriculum_vitae', $prefix, $post->ID);
			$curriculum_vitae_id = dci_get_meta('curriculum_vitae_id', $prefix, $post->ID);

			$biografia = dci_get_wysiwyg_field('biografia');
			$situazione_patrimoniale = dci_get_wysiwyg_field('situazione_patrimoniale');
			$ulteriori_informazioni = dci_get_wysiwyg_field('ulteriori_informazioni');

			$dichiarazione_redditi = dci_get_meta('dichiarazione_redditi', $prefix, $post->ID);
			$spese_elettorali = dci_get_meta('spese_elettorali', $prefix, $post->ID);
			$variazione_situazione_patrimoniale = dci_get_meta('variazione_situazione_patrimoniale', $prefix, $post->ID);
			$altre_cariche = dci_get_meta('altre_cariche', $prefix, $post->ID);

			$meta_pfx_incarico = dci_get_tipologie_prefixes()['incarico'] ?? null;
			$incarico = get_posts([
				'post_type' => 'incarico',
				'posts_per_page' => 1,
				'meta_query' => [[
					'key' => "{$meta_pfx_incarico}persona",
					'value' => $post->ID,
				]],
			]);
			$incarico = reset($incarico);
			if ( $incarico && $incarico instanceof WP_Post ) {
				$incarico_nome = get_the_title($incarico);

				$incarico_tipo = ucfirst( current( wp_list_pluck( get_the_terms($incarico->ID, 'tipi_incarico') ?: [], 'name' ) ) );

				$incarico_compensi = wpautop(get_post_meta($incarico->ID, "{$meta_pfx_incarico}compensi", true));
				$incarico_data_insediamento = get_post_meta($incarico->ID, "{$meta_pfx_incarico}data_insediamento", true);
			}


			$indice_pagina = [
				'incarico' => 'Incarico',
				'tipo-incarico' => 'Tipo di Incarico',
				'compensi' => 'Compensi',
				'data-insediamento' => 'Data di insediamento',
				'organizzazione' => 'Organizzazione',
				'competenze' => 'Competenze',
				'biografia' => 'Biografia',
				'contatti' => 'Contatti',
				'curriculum-vitae' => 'Curriculum vitae',
				'situazione-patrimoniale' => 'Situazione patrimoniale',
				'dichiarazione-redditi' => 'Dichiarazione dei redditi',
				'spese-elettorali' => 'Spese elettorali',
				'variazioni-situazione-patrimoniale' => 'Variazioni situazione patrimoniale',
				'altre-cariche' => 'Altre cariche',
			];

			$sezioni_pagina = [
				'incarico' => !empty($incarico_nome),
				'tipo-incarico' => !empty($incarico_tipo),
				'compensi' => !empty($incarico_compensi),
				'data-insediamento' => !empty($incarico_data_insediamento),
				'organizzazione' => !empty($organizzazioni),
				'competenze' => !empty($competenze),
				'biografia' => !empty($biografia),
				'contatti' => !empty($punti_contatto),
				'curriculum-vitae' => !empty($curriculum_vitae),
				'situazione-patrimoniale' => !empty($situazione_patrimoniale),
				'dichiarazione-redditi' => !empty($dichiarazione_redditi),
				'spese-elettorali' => !empty($spese_elettorali),
				'variazioni-situazione-patrimoniale' => !empty($variazione_situazione_patrimoniale),
				'altre-cariche' => !empty($altre_cariche),
			];

			$sezioni_pagina = array_filter($sezioni_pagina);
			$indice_pagina = array_intersect_key($indice_pagina, $sezioni_pagina);
		?>

			<div class="container" id="main-container">
				<div class="row justify-content-center">
					<div class="col-12 col-lg-10">
						<?php get_template_part("template-parts/common/breadcrumb"); ?>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-lg-10">
						<div class="cmp-heading pb-3 pb-lg-4">
							<div class="row">
								<div class="col-lg-8">
									<div class="titolo-sezione">
										<h1> <?php the_title(); ?></h1>
									</div>
									<p class="subtitle-small mb-3" data-element="service-description">
										<?php echo $descrizione_breve; ?>
									</p>
								</div>
								<div class="col-lg-3 offset-lg-1 mt-5 mt-lg-0">
									<?php
									$GLOBALS['hide_arguments'] = true;
									get_template_part('template-parts/single/actions');
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php if ( !empty($foto_url) ) : ?>
			<?php $foto_id = $foto_id ?: attachment_url_to_postid($foto_url); ?>
			<?php $foto_alt = $foto_id ? get_post_meta( $foto_id, '_wp_attachment_image_alt', true ) : ''; ?>
			<div class="container-fluid mb-4 hero-image">
				<div class="row">
					<figure class="figure px-0 img-full">
						<img
							class="figure-img img-fluid"
							src="<?php echo esc_url($foto_url); ?>"
							alt="<?php echo esc_attr($foto_alt); ?>"
						/>
					</figure>
				</div>
			</div>
		<?php endif; ?>

			<div class="container border-top border-light row-column-border">
				<div class="row row-column-menu-left">
					<div class="col-12 col-lg-3 pb-4 border-col">
						<div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
							<nav class="navbar it-navscroll-wrapper navbar-expand-lg" aria-label="Indice della pagina" data-bs-navscroll>
								<div class="navbar-custom" id="navbarNavProgress">
									<div class="menu-wrapper">
										<div class="link-list-wrapper">
											<div class="accordion">
												<div class="accordion-item">
												<span class="accordion-header" id="accordion-title-one">
													<button class="accordion-button pb-10 px-3 text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-one" aria-expanded="true" aria-controls="collapse-one">
														Indice della pagina
														<svg class="icon icon-xs right">
															<use href="#it-expand"></use>
														</svg>
													</button>
												</span>
													<div class="progress">
														<div class="progress-bar it-navscroll-progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
													<div id="collapse-one" class="accordion-collapse collapse show" role="region" aria-labelledby="accordion-title-one">
														<div class="accordion-body">
															<ul class="link-list" data-element="page-index">

																<?php foreach ( $indice_pagina as $id_sezione => $titolo_sezione ) : ?>
																<li class="nav-item">
																	<a class="nav-link" href="<?php echo "#$id_sezione"; ?>">
																		<span class="title-medium"><?php echo "$titolo_sezione"; ?></span>
																	</a>
																</li>
																<?php endforeach; ?>

															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</nav>
						</div>
					</div>
					<div class="col-12 col-lg-8 offset-lg-1 pt-4 pb-5">
						<div class="it-page-sections-container">

							<?php if ( !empty($incarico_nome) ) : ?>
							<section id="incarico" class="it-page-section mb-5">
								<h2 class="h4 my-3">Incarico</h2>
								<div class="richtext-wrapper lora">
									<?php echo $incarico_nome; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($incarico_tipo) ) : ?>
							<section id="tipo-incarico" class="it-page-section mb-5">
								<h2 class="h4 my-3">Tipo di incarico</h2>
								<div class="richtext-wrapper lora">
									<?php echo $incarico_tipo; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($incarico_compensi) ) : ?>
							<section id="compensi" class="it-page-section mb-5">
								<h2 class="h4 my-3">Compensi</h2>
								<div class="richtext-wrapper lora">
									<?php echo $incarico_compensi; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($incarico_data_insediamento) ) : ?>
							<section id="data-insediamento" class="it-page-section mb-5">
								<h2 class="h4 my-3">Data di insediamento</h2>
								<div class="richtext-wrapper lora">
									<?php echo $incarico_data_insediamento; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($organizzazioni) ) : ?>
							<section id="organizzazione" class="it-page-section mb-5">
								<h2 class="h4 my-3">Organizzazione</h2>
								<div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal"> <!-- TODO: card-teaser-light opp. card-teaser-100 -->
									<?php foreach ( $organizzazioni as $GLOBALS['uo_id'] ) : ?>
										<?php $GLOBALS['with_border'] = true; ?>
										<?php get_template_part("template-parts/unita-organizzativa/card"); ?>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($competenze) ) : ?>
							<section id="competenze" class="it-page-section mb-5">
								<h2 class="h4 my-3">Competenze</h2>
								<div class="richtext-wrapper lora">
									<?php echo $competenze; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($biografia) ) : ?>
							<section id="biografia" class="it-page-section mb-5">
								<h2 class="h4 my-3">Biografia</h2>
								<div class="richtext-wrapper lora">
									<?php echo $biografia; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($punti_contatto) ) : ?>
							<section id="contatti" class="it-page-section mb-5">
								<h2 class="h4 my-3">Contatti</h2>
								<div class="row">
									<?php foreach ( $punti_contatto as $GLOBALS['pc_id'] ) : ?>
									<div class="col-12">
										<?php get_template_part("template-parts/punto-contatto/card"); ?>
									</div>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($curriculum_vitae) ) : ?>
							<section id="curriculum-vitae" class="it-page-section mb-5">
								<h2 class="h4 my-3">Curriculum vitae</h2>
								<div class="card-wrapper card-teaser-wrapper">
									<?php if ( !empty($curriculum_vitae_id) ) : ?>
										<?php
										$GLOBALS['nomefile'] = get_attached_file($curriculum_vitae_id);
										$GLOBALS['idfile'] = $curriculum_vitae_id;
										get_template_part("template-parts/documento/file");
										?>
									<?php elseif ( filter_var($curriculum_vitae, FILTER_VALIDATE_URL) ) : ?>
										<?php // TODO... ?>
									<?php endif; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($situazione_patrimoniale) ) : ?>
							<section id="situazione-patrimoniale" class="it-page-section mb-5">
								<h2 class="h4 my-3">Situazione patrimoniale</h2>
								<div class="richtext-wrapper lora">
									<?php echo $situazione_patrimoniale; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($dichiarazione_redditi) ) : ?>
							<section id="dichiarazione-redditi" class="it-page-section mb-5">
								<h2 class="h4 my-3">Dichiarazione dei redditi</h2>
								<div class="card-wrapper card-teaser-wrapper">
									<?php foreach ( $dichiarazione_redditi as $allegato_id => $allegato_url ) : ?>
										<?php
										$GLOBALS['nomefile'] = $allegato_url;
										$GLOBALS['idfile'] = $allegato_id;
										get_template_part("template-parts/documento/file");
										?>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($spese_elettorali) ) : ?>
							<section id="spese-elettorali" class="it-page-section mb-5">
								<h2 class="h4 my-3">Spese elettorali</h2>
								<p class="lora">
								Le spese sostenute e le obbligazioni assunte per la propaganda elettorale.
								</p>
								<div class="card-wrapper card-teaser-wrapper">
									<?php foreach ( $spese_elettorali as $allegato_id => $allegato_url ) : ?>
										<?php
										$GLOBALS['nomefile'] = $allegato_url;
										$GLOBALS['idfile'] = $allegato_id;
										get_template_part("template-parts/documento/file");
										?>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($variazione_situazione_patrimoniale) ) : ?>
							<section id="variazioni-situazione-patrimoniale" class="it-page-section mb-5">
								<h2 class="h4 my-3">Variazioni situazione patrimoniale</h2>
								<div class="card-wrapper card-teaser-wrapper">
									<?php foreach ( $spese_elettorali as $allegato_id => $allegato_url ) : ?>
										<?php
										$GLOBALS['nomefile'] = $allegato_url;
										$GLOBALS['idfile'] = $allegato_id;
										get_template_part("template-parts/documento/file");
										?>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>

							<?php if ( !empty($altre_cariche) ) : ?>
							<section id="altre-cariche" class="it-page-section mb-5">
								<h2 class="h4 my-3">Altre cariche</h2>
								<div class="card-wrapper card-teaser-wrapper">
									<?php foreach ( $altre_cariche as $allegato_id => $allegato_url ) : ?>
										<?php
										$GLOBALS['nomefile'] = $allegato_url;
										$GLOBALS['idfile'] = $allegato_id;
										get_template_part("template-parts/documento/file");
										?>
									<?php endforeach; ?>
								</div>
							</section>
							<?php endif; ?>


							<?php get_template_part('template-parts/single/page_bottom', 'simple'); ?>
						</div>
					</div>
				</div>
			</div>
			<?php get_template_part("template-parts/common/valuta-servizio"); ?>
			<?php get_template_part('template-parts/single/more-posts', 'carousel'); ?>
			<?php get_template_part("template-parts/common/assistenza-contatti"); ?>

		<?php
		endwhile; // End of the loop.
		?>
	</main>
<?php
get_footer();
