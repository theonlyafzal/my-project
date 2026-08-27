<?php
/**
 * Shortcode: Download History - [download_history]
 *
 * @package EDD
 * @category Template
 *
 * @since 3.0 Uses new `edd_get_orders()` function and associated helpers.
 *            Checks status on individual order items when determining download link visibility.
 */

if ( ! empty( $_GET['edd-verify-success'] ) ) : ?>
    <p class="edd-account-verified edd_success">
        <?php esc_html_e( 'Your account has been successfully verified!', 'easy-digital-downloads' ); ?>
    </p>
    <?php
endif;
/**
 * This template is used to display the download history of the current user.
 */
$customer = edd_get_customer_by( 'user_id', get_current_user_id() );
$page     = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

if ( ! empty( $customer ) ) {
    $orders = edd_get_orders(
        array(
            'customer_id'    => $customer->id,
            'number'         => 20,
            'offset'         => 20 * ( intval( $page ) - 1 ),
            'type'           => 'sale',
            'status__not_in' => array( 'trash', 'refunded', 'abandoned' ),
        )
    );
} else {
    $orders = array();
}

if ( $orders ) :
    do_action( 'edd_before_download_history' ); ?>
    <div class="edd-download-history">
        <table id="edd_user_history" class="edd-table">
            <thead>
                <tr class="edd_download_history_row">
                    <?php do_action( 'edd_download_history_header_start' ); ?>
                    <th class="edd_download_download_name"><?php esc_html_e( 'Download Name', 'easy-digital-downloads' ); ?></th>
                    <?php if ( ! edd_no_redownload() ) : ?>
                        <th class="edd_download_download_files"><?php esc_html_e( 'Files', 'easy-digital-downloads' ); ?></th>
                    <?php endif; ?>
                    <?php do_action( 'edd_download_history_header_end' ); ?>
                </tr>
            </thead>
            <?php
            foreach ( $orders as $order ) :
                foreach ( $order->get_items_with_bundles() as $key => $item ) :
                    ?>
                    <tr class="edd_download_history_row">
                        <td class="edd_download_download_name"><?php echo esc_html( $item->product_name ); ?></td>
                        <?php if ( ! edd_no_redownload() ) : ?>
                            <td class="edd_download_download_files">
                                <?php
                                $download_files = edd_get_download_files( $item->product_id, $item->price_id );
                                if ( $item->is_deliverable() ) {
                                    if ( $download_files ) {
                                        foreach ( $download_files as $filekey => $file ) {
                                            $download_url = edd_get_download_file_url( $order, $order->email, $filekey, $item->product_id, $item->price_id );
                                            ?>
                                            <div class="edd_download_file">
                                                <a href="<?php echo esc_url( $download_url ); ?>" class="edd_download_file_link">
                                                    <?php echo esc_html( edd_get_file_name( $file ) ); ?>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        esc_html_e( 'No downloadable files found.', 'easy-digital-downloads' );
                                    }
                                } else {
                                    printf(
                                        esc_html__( 'Status: %s', 'easy-digital-downloads' ),
                                        esc_html( edd_get_status_label( $item->status ) )
                                    );
                                }
                                ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php
                endforeach;
            endforeach;
            ?>
        </table>
        <?php
        if ( ! empty( $customer->id ) ) {
            $count = edd_count_orders(
                array(
                    'customer_id'    => $customer->id,
                    'type'           => 'sale',
                    'status__not_in' => array( 'trash', 'refunded', 'abandoned' ),
                )
            );
            echo '<div class="mayosis-edd-p-history-pagination">';
            $total_pages = ceil( $count / 20 ); // 20 items per page
            for ( $i = 1; $i <= $total_pages; $i++ ) {
                $active_class = $i == $page ? ' class="active"' : '';
                echo '<a href="#" data-page="' . $i . '" data-type="download_history"' . $active_class . '>' . $i . '</a> ';
            }
            echo '</div>';
        }
        ?>
        <div class="msps_loading-icon" style="display: none;">
           <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        </div>
    </div>
    <?php do_action( 'edd_after_download_history' ); ?>
<?php else : ?>
    <p class="edd-no-downloads"><?php esc_html_e( 'You have not purchased any downloads', 'easy-digital-downloads' ); ?></p>
<?php endif; ?>
