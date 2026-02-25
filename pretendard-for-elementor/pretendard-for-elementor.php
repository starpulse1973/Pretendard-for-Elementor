<?php
/**
 * Plugin Name: 엘리멘터용 프리텐다드 가변폰트 (CDN)
 * Plugin URI:  https://socialbridge.co.kr
 * Description: 별도의 폰트 업로드없이 Elementor 편집기 목록에 Pretendard 폰트를 추가하고 사용 가능하게 해줍니다. 최신 CDN을 통해 로드합니다. (v1.3.9 CDN 사용)
 * Version: 1.0.1
 * Author: Social Bridge Dev. Team
 * Author URI:  https://socialbridge.co.kr
 * Requires at least: 6.9
 * Requires PHP: 8.1
 * License:     GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * 1. 최신 가변 폰트 CDN 로드 (v1.3.9 기준)
 */
function pve_load_assets() {
    // 가변 폰트 전용 스타일시트
    wp_enqueue_style( 
        'pretendard-variable-style', 
        'https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.min.css', 
        array(), 
        '1.3.9' 
    );
}
add_action( 'wp_enqueue_scripts', 'pve_load_assets' );
add_action( 'elementor/editor/after_enqueue_scripts', 'pve_load_assets' );

/**
 * 2. Elementor 목록에 폰트 등록
 */
add_filter( 'elementor/fonts/groups', function( $font_groups ) {
    $font_groups['pve_custom'] = 'Variable Fonts';
    return $font_groups;
});

add_filter( 'elementor/fonts/additional_fonts', function( $additional_fonts ) {
    // 이 이름이 Elementor 드롭다운에 표시되며, CSS font-family와 일치해야 합니다.
    $additional_fonts['Pretendard Variable'] = 'pve_custom';
    return $additional_fonts;
});

/**
 * 3. 편집기 내 UI 최적화
 */
add_action( 'elementor/editor/before_enqueue_scripts', function() {
    echo '
    <style>
        /* 드롭다운 목록 내 폰트 미리보기 구현 */
        .elementor-font-Pretendard-Variable {
            font-family: "Pretendard Variable", sans-serif !important;
        }
        /* 선택 시 가변 폰트 특성(두께 등)이 즉각 반영되도록 설정 */
        body.elementor-editor-active {
            font-family: "Pretendard Variable", sans-serif;
        }
    </style>
    ';
});

/**
 * 4. 사이트 전체 기본 가변 폰트 설정 (선택 사항)
 * Elementor 설정에서 직접 선택하지 않아도 기본 적용되길 원할 경우 활성화하세요.
 */
add_action( 'wp_head', function() {
    echo '<style> :root { --pve-font: "Pretendard Variable", sans-serif; } </style>';
}, 1 );