<section class="sponsor-block">
    <div class="container">
        <div class="top-block">
            <h2 class="text-black"><?php echo get_sub_field( 'title' ); ?></h2>
        </div>
        <div class="table-block desktop">
            <div class="table-top-row table-row">
                <div class="title-column">
                </div>
                <div class="column diamond-column">
                    <span class="table-title">Diamond</span>
                </div>
                <div class="column platinum-column">
                    <span class="table-title">Platinum</span>
                </div>
                <div class="column gold-column">
                    <span class="table-title">Gold</span>
                </div>
                <div class="column silver-column">
                    <span class="table-title">Silver</span>
                </div>
            </div>
            <?php if ( have_rows( 'tabel_rows' ) ) : ?>
                <div class="table-content">
                    <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?>
                        <div class="table-row">
                            <div class="title-column">
                                <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                               
                                    <?php if ( have_rows( 'tooltip' ) ) : ?>
                                        <span class="tooltip-icon">
                                            <?php while ( have_rows( 'tooltip' ) ) : the_row(); ?>
                                                <span class="tooltip">
                                                    <span class="tooltip-title"><?php echo get_sub_field( 'tooltip_title' ); ?></span>
                                                    <span class="tooltip-text"><?php echo get_sub_field( 'tooltip_text' ); ?></span>
                                                </span>
                                            <?php endwhile; ?>
                                        </span>
                                    <?php else : ?>
                                        <?php // no rows found ?>
                                    <?php endif; ?>                                                                                
                                </span> 
                            </div>
                            <div class="column diamond-column">
                                <span class="table-item <?php echo get_sub_field( 'diamond' ); ?>">
                                    <span class="table-icon <?php echo get_sub_field( 'diamond' ); ?>"></span>
                                    <span class="table-text"><?php echo get_sub_field( 'diamond_text' ); ?></span>
                                </span>
                            </div>
                            <div class="column platinum-column">
                                <span class="table-item <?php echo get_sub_field( 'platinum' ); ?>">
                                    <span class="table-icon <?php echo get_sub_field( 'platinum' ); ?>"></span>
                                    <span class="table-text"><?php echo get_sub_field( 'platinum_text' ); ?></span>
                                </span>
                            </div>
                            <div class="column gold-column">
                                <span class="table-item <?php echo get_sub_field( 'gold' ); ?>">
                                    <span class="table-icon <?php echo get_sub_field( 'gold' ); ?>"></span>
                                    <span class="table-text"><?php echo get_sub_field( 'gold_text' ); ?></span>
                                </span>
                            </div>
                            <div class="column silver-column">
                                <span class="table-item <?php echo get_sub_field( 'silver' ); ?>">
                                    <span class="table-icon <?php echo get_sub_field( 'silver' ); ?>"></span>
                                    <span class="table-text"><?php echo get_sub_field( 'silver_text' ); ?></span>
                                </span>
                            </div> 
                        </div>
                    <?php endwhile; ?>
                </div>
                
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
        <div class="table-block mobile">
            <?php if ( have_rows( 'tabel_rows' ) ) : ?>
                <div class="sponsors-slider-container">
                    <div class="slide diamond-slide">
                        <div class="table">
                            <div class="table-title">
                                <span class="title">Diamond</span>
                            </div>
                            <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?> 
                                <?php if ( get_sub_field( 'mobile_inclusions' ) != 1 ) { ?>   
                                    <?php if ( get_sub_field( 'diamond' ) != 'no' ) { ?>                
                                        <div class="table-row">                              
                                            <div class="title-column">
                                                <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                               
                                                    <?php if ( have_rows( 'tooltip' ) ) : ?>
                                                        <span class="tooltip-icon"></span>
                                                        <?php while ( have_rows( 'tooltip' ) ) : the_row(); ?>
                                                            <span class="tooltip mobile">
                                                                <span class="tooltip-close">x</span>
                                                                <span class="tooltip-title"><?php echo get_sub_field( 'tooltip_title' ); ?></span>
                                                                <span class="tooltip-text"><?php echo get_sub_field( 'tooltip_text' ); ?></span>
                                                            </span>
                                                        <?php endwhile; ?>                                                        
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>                                                                                
                                                </span> 
                                            </div>
                                            <div class="answer-column diamond-column">
                                                <span class="table-item <?php echo get_sub_field( 'diamond' ); ?>">
                                                    <span class="table-icon <?php echo get_sub_field( 'diamond' ); ?>"></span>
                                                    <span class="table-text">
                                                        <?php $value = get_sub_field( 'diamond_text' );;
                                                            echo strtok($value, " ");
                                                        ?>                                                
                                                    </span>
                                                </span>
                                            </div>                                
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php endwhile; ?>
                            <div class="inclusions">
                                <span class="inclusions-container">
                                    <span class="inclusion-small-text">Also includes</span>
                                    <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?>                                     
                                        <?php if ( get_sub_field( 'mobile_inclusions' ) == 1 ) { ?>   
                                            <?php if ( get_sub_field( 'diamond' ) != 'no' ) { ?>                
                                                <div class="table-row">                              
                                                    <div class="title-column">
                                                        <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                                                                                                                                                  
                                                        </span> 
                                                    </div>                                                                              
                                                </div>
                                            <?php } ?>
                                        <?php } ?>                                   
                                    <?php endwhile; ?>
                                </span>
                                <span class="inclusion-trigger">View all inclusions</span>                                
                            </div>
                        </div>
                    </div>
                    <div class="slide platinum-slide">
                        <div class="table">
                            <div class="table-title">
                                <span class="title">Platinum</span>
                            </div>
                            <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?> 
                                <?php if ( get_sub_field( 'mobile_inclusions' ) != 1 ) { ?>   
                                    <?php if ( get_sub_field( 'platinum' ) != 'no' ) { ?>
                                        <div class="table-row">                           
                                            <div class="title-column">
                                                <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                               
                                                    <?php if ( have_rows( 'tooltip' ) ) : ?>
                                                        <span class="tooltip-icon"></span>
                                                        <?php while ( have_rows( 'tooltip' ) ) : the_row(); ?>
                                                            <span class="tooltip mobile">
                                                                <span class="tooltip-close">x</span>
                                                                <span class="tooltip-title"><?php echo get_sub_field( 'tooltip_title' ); ?></span>
                                                                <span class="tooltip-text"><?php echo get_sub_field( 'tooltip_text' ); ?></span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>                                                                                
                                                </span> 
                                            </div>                               
                                            <div class="answer-column platinum-column">
                                                <span class="table-item <?php echo get_sub_field( 'platinum' ); ?>">
                                                    <span class="table-icon <?php echo get_sub_field( 'platinum' ); ?>"></span>
                                                    <span class="table-text">
                                                        <?php $value = get_sub_field( 'platinum_text' );;
                                                            echo strtok($value, " ");
                                                        ?> 
                                                    </span>
                                                </span>
                                            </div>
                                        </div>  
                                    <?php } ?>
                                <?php } ?>                                                         
                            <?php endwhile; ?>
                            <div class="inclusions">
                                 <span class="inclusions-container">
                                    <span class="inclusion-small-text">Also includes</span>
                                    <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?>                                     
                                        <?php if ( get_sub_field( 'mobile_inclusions' ) == 1 ) { ?>   
                                            <?php if ( get_sub_field( 'platinum' ) != 'no' ) { ?>                
                                                <div class="table-row">                              
                                                    <div class="title-column">
                                                        <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                                                                                                                                                  
                                                        </span> 
                                                    </div>                                                                              
                                                </div>
                                            <?php } ?>
                                        <?php } ?>                                   
                                    <?php endwhile; ?>
                                </span>
                                <span class="inclusion-trigger">View all inclusions</span>                               
                            </div>
                        </div>
                    </div>
                    <div class="slide gold-slide">
                        <div class="table">
                            <div class="table-title">
                                <span class="title">Gold</span>
                            </div>
                            <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?>  
                                <?php if ( get_sub_field( 'mobile_inclusions' ) != 1 ) { ?>   
                                    <?php if ( get_sub_field( 'gold' ) != 'no' ) { ?>
                                        <div class="table-row">                          
                                            <div class="title-column">
                                               <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                               
                                                    <?php if ( have_rows( 'tooltip' ) ) : ?>
                                                        <span class="tooltip-icon"></span>
                                                        <?php while ( have_rows( 'tooltip' ) ) : the_row(); ?>
                                                            <span class="tooltip mobile">
                                                                <span class="tooltip-close">x</span>
                                                                <span class="tooltip-title"><?php echo get_sub_field( 'tooltip_title' ); ?></span>
                                                                <span class="tooltip-text"><?php echo get_sub_field( 'tooltip_text' ); ?></span>
                                                            </span>
                                                        <?php endwhile; ?>                                                        
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>                                                                                
                                                </span> 
                                            </div>
                                            <div class="answer-column gold-column">
                                                <span class="table-item <?php echo get_sub_field( 'gold' ); ?>">
                                                    <span class="table-icon <?php echo get_sub_field( 'gold' ); ?>"></span>
                                                    <span class="table-text">
                                                        <?php $value = get_sub_field( 'gold_text' );;
                                                            echo strtok($value, " ");
                                                        ?> 
                                                    </span>
                                                </span>
                                            </div> 
                                        </div>  
                                    <?php } ?>
                                <?php } ?>                                                        
                            <?php endwhile; ?>
                            <div class="inclusions">
                                <span class="inclusions-container">
                                    <span class="inclusion-small-text">Also includes</span>
                                    <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?>                                     
                                        <?php if ( get_sub_field( 'mobile_inclusions' ) == 1 ) { ?>   
                                            <?php if ( get_sub_field( 'gold' ) != 'no' ) { ?>                
                                                <div class="table-row">                              
                                                    <div class="title-column">
                                                        <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                                                                                                                                                  
                                                        </span> 
                                                    </div>                                                                              
                                                </div>
                                            <?php } ?>
                                        <?php } ?>                                   
                                    <?php endwhile; ?>
                                </span>
                                <span class="inclusion-trigger">View all inclusions</span>                                
                            </div>
                        </div>
                    </div>
                    <div class="slide silver-slide">
                         <div class="table">
                            <div class="table-title">
                                <span class="title">Silver</span>
                            </div>
                            <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?> 
                                <?php if ( get_sub_field( 'mobile_inclusions' ) != 1 ) { ?>   
                                    <?php if ( get_sub_field( 'silver' ) != 'no' ) { ?>
                                        <div class="table-row">                          
                                            <div class="title-column">
                                                <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                               
                                                    <?php if ( have_rows( 'tooltip' ) ) : ?>
                                                        <span class="tooltip-icon"></span>
                                                        <?php while ( have_rows( 'tooltip' ) ) : the_row(); ?>
                                                            <span class="tooltip mobile">
                                                                <span class="tooltip-close">x</span>
                                                                <span class="tooltip-title"><?php echo get_sub_field( 'tooltip_title' ); ?></span>
                                                                <span class="tooltip-text"><?php echo get_sub_field( 'tooltip_text' ); ?></span>
                                                            </span>
                                                        <?php endwhile; ?>
                                                    <?php else : ?>
                                                        <?php // no rows found ?>
                                                    <?php endif; ?>                                                                                
                                                </span> 
                                            </div>                                
                                            <div class="answer-column silver-column">
                                                <span class="table-item <?php echo get_sub_field( 'silver' ); ?>">
                                                    <span class="table-icon <?php echo get_sub_field( 'silver' ); ?>"></span>
                                                    <span class="table-text">
                                                        <?php $value = get_sub_field( 'silver_text' );;
                                                            echo strtok($value, " ");
                                                        ?>
                                                    </span>
                                                </span>
                                            </div>
                                        </div> 
                                    <?php } ?>
                                <?php } ?>                             
                            <?php endwhile; ?>
                            <div class="inclusions">
                                <span class="inclusions-container">
                                    <span class="inclusion-small-text">Also includes</span>
                                    <?php while ( have_rows( 'tabel_rows' ) ) : the_row(); ?>                                     
                                        <?php if ( get_sub_field( 'mobile_inclusions' ) == 1 ) { ?>   
                                            <?php if ( get_sub_field( 'silver' ) != 'no' ) { ?>                
                                                <div class="table-row">                              
                                                    <div class="title-column">
                                                        <span class="table-title"><?php echo get_sub_field( 'title' ); ?>                                                                                                                                                  
                                                        </span> 
                                                    </div>                                                                              
                                                </div>
                                            <?php } ?>
                                        <?php } ?>                                   
                                    <?php endwhile; ?>
                                </span>
                                <span class="inclusion-trigger">View all inclusions</span>                                
                            </div>
                        </div>
                    </div>
                </div>
               
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
        <div class="bottom-block">
             <?php if ( have_rows( 'button' ) ) : ?>
                <span class="button-container">
                    <?php while ( have_rows( 'button' ) ) : the_row(); ?>
                        <?php if(get_sub_field( 'link_type' ) == 'scrollto') { ?>
                            <a class="scroll-to-button std-button  red-button" href="#<?php echo get_sub_field( 'scroll_to_id' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } else { ?>
                            <a class="link std-button red-button" href="<?php echo get_sub_field( 'link' ); ?>" target="<?php echo get_sub_field( 'link_target' ); ?>"><?php echo get_sub_field( 'link_text' ); ?></a>
                        <?php } ?>
                    <?php endwhile; ?>
                </span>
            <?php else : ?>
                <?php // no rows found ?>
            <?php endif; ?>
        </div>
    </div>
</section>