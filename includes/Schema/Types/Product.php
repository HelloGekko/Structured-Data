<?php
/**
 * Product schema type.
 *
 * @package HelloGekko\StructuredData
 */

declare( strict_types=1 );

namespace HelloGekko\StructuredData\Schema\Types;

use HelloGekko\StructuredData\Schema\AbstractSchemaType;

defined( 'ABSPATH' ) || exit;

/**
 * Schema.org Product.
 */
class Product extends AbstractSchemaType {

	public function key(): string {
		return 'Product';
	}

	public function label(): string {
		return __( 'Product', 'hg-structured-data' );
	}

	public function group(): string {
		return __( 'Local & Commerce', 'hg-structured-data' );
	}

	public function supports_reviews(): bool {
		return true;
	}

	public function recommended(): array {
		return [
			'name'                          => [
				'label'       => __( 'Name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'description'                   => [
				'label'       => __( 'Description', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'image'                         => [
				'label'       => __( 'Image (URL)', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'image',
			],
			'sku'                           => [
				'label' => __( 'SKU', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'gtin'                          => [
				'label' => __( 'GTIN', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'mpn'                           => [
				'label' => __( 'MPN', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'brand.name'                    => [
				'label'       => __( 'Brand name', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'offers.price'                  => [
				'label'       => __( 'Price', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'number',
			],
			'offers.priceCurrency'          => [
				'label'       => __( 'Price currency', 'hg-structured-data' ),
				'recommended' => true,
				'type'        => 'text',
			],
			'offers.availability'           => [
				'label' => __( 'Availability', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.url'                    => [
				'label' => __( 'Offer URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
			'offers.priceValidUntil'        => [
				'label' => __( 'Price valid until', 'hg-structured-data' ),
				'type'  => 'date',
			],
			'offers.hasMerchantReturnPolicy.applicableCountry' => [
				'label' => __( 'Return policy — country (e.g. NL)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.hasMerchantReturnPolicy.returnPolicyCategory' => [
				'label' => __( 'Return policy — category', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.hasMerchantReturnPolicy.merchantReturnDays' => [
				'label' => __( 'Return policy — days to return', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'offers.hasMerchantReturnPolicy.returnMethod' => [
				'label' => __( 'Return policy — method', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.hasMerchantReturnPolicy.returnFees' => [
				'label' => __( 'Return policy — fees', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.shippingDetails.shippingRate.value' => [
				'label' => __( 'Shipping — rate', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'offers.shippingDetails.shippingRate.currency' => [
				'label' => __( 'Shipping — currency', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'offers.shippingDetails.shippingDestination.addressCountry' => [
				'label' => __( 'Shipping — destination country (e.g. NL)', 'hg-structured-data' ),
				'type'  => 'text',
			],
			'aggregateRating.ratingValue'   => [
				'label' => __( 'Rating value', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'aggregateRating.reviewCount'   => [
				'label' => __( 'Review count', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'aggregateRating.bestRating'    => [
				'label' => __( 'Best rating', 'hg-structured-data' ),
				'type'  => 'number',
			],
			'url'                           => [
				'label' => __( 'URL', 'hg-structured-data' ),
				'type'  => 'url',
			],
		];
	}

	public function nested_types(): array {
		return [
			'brand'           => 'Brand',
			'offers'          => 'Offer',
			'aggregateRating' => 'AggregateRating',
			'offers.hasMerchantReturnPolicy' => 'MerchantReturnPolicy',
			'offers.shippingDetails' => 'OfferShippingDetails',
			'offers.shippingDetails.shippingRate' => 'MonetaryAmount',
			'offers.shippingDetails.shippingDestination' => 'DefinedRegion',
		];
	}

	public function default_mappings(): array {
		return [
			[ 'property' => 'name', 'source' => 'wp', 'value' => 'post_title' ],
			[ 'property' => 'description', 'source' => 'wp', 'value' => 'post_excerpt' ],
			[ 'property' => 'image', 'source' => 'wp', 'value' => 'featured_image' ],
			[ 'property' => 'url', 'source' => 'wp', 'value' => 'permalink' ],
		];
	}
}
