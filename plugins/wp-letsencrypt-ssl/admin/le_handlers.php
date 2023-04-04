<?php

require_once WPLE_DIR . 'classes/le-core.php';
/**
 * Todo:
 * A file to disable force https completely when site lockout
 */
class WPLE_Handler
{
    public function __construct()
    {
        add_action( 'admin_init', [ $this, 'admin_init_handlers' ], 1 );
    }
    
    public function admin_init_handlers()
    {
        $this->wple_auto_handler();
        $this->primary_ssl_install_request();
        $this->force_https_upon_success();
        $this->wple_download_files();
        $this->wple_intro_pricing_handler();
    }
    
    public function wple_auto_handler()
    {
        
        if ( isset( $_GET['wpleauto'] ) ) {
            $leopts = get_option( 'wple_opts' );
            new WPLE_Core( $leopts );
            //continue verification
        }
    
    }
    
    private function primary_ssl_install_request()
    {
        //single domain ssl
        
        if ( isset( $_POST['generate-certs'] ) ) {
            if ( !wp_verify_nonce( $_POST['letsencrypt'], 'legenerate' ) || !current_user_can( 'manage_options' ) ) {
                die( 'Unauthorized request' );
            }
            if ( empty($_POST['wple_email']) ) {
                wp_die( esc_html__( 'Please input valid email address', 'wp-letsencrypt-ssl' ) );
            }
            $leopts = array(
                'email'           => sanitize_email( $_POST['wple_email'] ),
                'date'            => date( 'd-m-Y' ),
                'expiry'          => '',
                'type'            => 'single',
                'send_usage'      => ( isset( $_POST['wple_send_usage'] ) ? 1 : 0 ),
                'include_www'     => ( isset( $_POST['wple_include_www'] ) ? 1 : 0 ),
                'include_mail'    => ( isset( $_POST['wple_include_mail'] ) ? 1 : 0 ),
                'include_webmail' => ( isset( $_POST['wple_include_webmail'] ) ? 1 : 0 ),
                'agree_gws_tos'   => ( isset( $_POST['wple_agree_gws_tos'] ) ? 1 : 0 ),
                'agree_le_tos'    => ( isset( $_POST['wple_agree_le_tos'] ) ? 1 : 0 ),
            );
            
            if ( isset( $_POST['wple_domain'] ) && !is_multisite() ) {
                $leopts['subdir'] = 1;
                //flag domain as primary domain of subdir site
                $leopts['domain'] = sanitize_text_field( $_POST['wple_domain'] );
            }
            
            update_option( 'wple_opts', $leopts );
            WPLE_Trait::wple_cpanel_identity();
            new WPLE_Core( $leopts );
        }
    
    }
    
    private function force_https_upon_success()
    {
        //since 2.4.0
        //force https upon success
        
        if ( isset( $_POST['wple-https'] ) ) {
            if ( !wp_verify_nonce( $_POST['sslready'], 'wplehttps' ) || !current_user_can( 'manage_options' ) ) {
                exit( 'Unauthorized access' );
            }
            $basedomain = str_ireplace( array( 'http://', 'https://' ), array( '', '' ), addslashes( site_url() ) );
            //4.7
            if ( FALSE != stripos( $basedomain, '/' ) ) {
                $basedomain = substr( $basedomain, 0, stripos( $basedomain, '/' ) );
            }
            $client = WPLE_Trait::wple_verify_ssl( $basedomain );
            
            if ( !$client && !is_ssl() ) {
                wp_redirect( admin_url( '/admin.php?page=wp_encryption&success=1&nossl=1', 'http' ) );
                exit;
            }
            
            // $SSLCheck = @fsockopen("ssl://" . $basedomain, 443, $errno, $errstr, 30);
            // if (!$SSLCheck) {
            //   wp_redirect(admin_url('/admin.php?page=wp_encryption&success=1&nossl=1', 'http'));
            //   exit();
            // }
            ///$reverter = uniqid('wple');
            $savedopts = get_option( 'wple_opts' );
            $savedopts['force_ssl'] = 1;
            ///$savedopts['revertnonce'] = $reverter;
            ///WPLE_Trait::wple_send_reverter_secret($reverter);
            update_option( 'wple_opts', $savedopts );
            delete_option( 'wple_error' );
            //complete
            update_option( 'wple_ssl_screen', 'success' );
            update_option( 'siteurl', str_ireplace( 'http:', 'https:', get_option( 'siteurl' ) ) );
            update_option( 'home', str_ireplace( 'http:', 'https:', get_option( 'home' ) ) );
            wp_redirect( admin_url( '/admin.php?page=wp_encryption', 'https' ) );
            exit;
        }
    
    }
    
    /**
     * Download cert files based on clicked link
     *
     * certs for multisite mapped domains cannot be downloaded yet
     * @since 1.0.0
     * @return void
     */
    public function wple_download_files()
    {
        
        if ( isset( $_GET['le'] ) && current_user_can( 'manage_options' ) ) {
            switch ( $_GET['le'] ) {
                case '1':
                    $file = uniqid() . '-cert.crt';
                    file_put_contents( $file, file_get_contents( ABSPATH . 'keys/certificate.crt' ) );
                    break;
                case '2':
                    $file = uniqid() . '-key.pem';
                    file_put_contents( $file, file_get_contents( ABSPATH . 'keys/private.pem' ) );
                    break;
                case '3':
                    $file = uniqid() . '-cabundle.crt';
                    
                    if ( file_exists( ABSPATH . 'keys/cabundle.crt' ) ) {
                        $cabundlefile = file_get_contents( ABSPATH . 'keys/cabundle.crt' );
                    } else {
                        $cabundlefile = file_get_contents( WPLE_DIR . 'cabundle/ca.crt' );
                    }
                    
                    file_put_contents( $file, $cabundlefile );
                    break;
            }
            header( 'Content-Description: File Transfer' );
            header( 'Content-Type: text/plain' );
            header( 'Content-Length: ' . filesize( $file ) );
            header( 'Content-Disposition: attachment; filename=' . basename( $file ) );
            readfile( $file );
            if ( file_exists( $file ) ) {
                unlink( $file );
            }
            exit;
        }
    
    }
    
    /**
     * Intro pricing table handler
     * 
     * @since 5.0.0     
     * @return void
     */
    public function wple_intro_pricing_handler()
    {
        $goplan = '';
        
        if ( isset( $_GET['gofree'] ) ) {
            update_option( 'wple_plan_choose', 1 );
            wp_redirect( admin_url( '/admin.php?page=wp_encryption' ), 302 );
            exit;
        } else {
            
            if ( isset( $_GET['gopro'] ) ) {
                update_option( 'wple_plan_choose', 1 );
                
                if ( $_GET['gopro'] == 2 ) {
                    //unlimited
                    wp_redirect( admin_url( '/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=8210&plan_name=pro&billing_cycle=annual&pricing_id=10873&currency=usd' ), 302 );
                } else {
                    
                    if ( $_GET['gopro'] == 3 ) {
                        //annual
                        wp_redirect( admin_url( '/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=8210&plan_name=pro&billing_cycle=annual&pricing_id=7965&currency=usd' ), 302 );
                    } else {
                        //single lifetime
                        wp_redirect( admin_url( '/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=8210&plan_name=pro&billing_cycle=lifetime&pricing_id=7965&currency=usd' ), 302 );
                    }
                
                }
                
                exit;
            } else {
                
                if ( isset( $_GET['gofirewall'] ) ) {
                    update_option( 'wple_plan_choose', 1 );
                    ///wp_redirect(admin_url('/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=11394&plan_name=pro&billing_cycle=annual&pricing_id=11717&currency=usd'), 302);
                    wp_redirect( admin_url( '/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=8210&plan_name=pro&billing_cycle=annual&pricing_id=7965&currency=usd' ), 302 );
                    exit;
                } else {
                    
                    if ( isset( $_GET['gositelock'] ) ) {
                        update_option( 'wple_plan_choose', 1 );
                        ///wp_redirect(admin_url('/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=11394&plan_name=pro&billing_cycle=annual&pricing_id=11717&currency=usd'), 302);
                        wp_redirect( admin_url( '/admin.php?page=wp_encryption-pricing&checkout=true&plan_id=20784&plan_name=sitelock&billing_cycle=annual&currency=usd' ), 302 );
                        exit;
                    }
                
                }
            
            }
        
        }
    
    }

}