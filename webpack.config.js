const webpack                        = require( 'webpack' )
const { resolve }                    = require( 'path' )
const MiniCssExtractPlugin           = require( 'mini-css-extract-plugin' )
const RtlCssPlugin                   = require( 'rtlcss-webpack-plugin' )
const RemoveEmptyScriptsPlugin       = require( 'webpack-remove-empty-scripts' )
const TerserPlugin                   = require( 'terser-webpack-plugin' )
const { sentryWebpackPlugin }        = require( '@sentry/webpack-plugin' )

const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' )
const postcssPlugins = require( '@wordpress/postcss-plugins-preset' )

const isProduction = process.argv[ process.argv.indexOf( '--mode' ) + 1 ] === 'production'

// Main configuration.
const config = {
	output: {
		filename: 'js/[name].min.js',
		path: resolve( __dirname, 'admin/assets/dist/' ),
		clean: true
	},
	devtool: 'source-map',
	resolve: {
		extensions: [ '.jsx', '.ts', '.tsx', '.js' ]
	},
	optimization: {
		// Only concatenate modules in production, when not analyzing bundles.
		concatenateModules: isProduction,
		minimizer: [
			new TerserPlugin( {
				parallel: true,
				terserOptions: {
					output: {
						comments: /translators:/i
					},
					compress: {
						passes: 2
					},
					mangle: {
						reserved: [ '__', '_n', '_nx', '_x' ]
					},
					sourceMap: true
				},
				extractComments: false
			} )
		]
	},
	resolve: {
		extensions: [ '.js', '.jsx', '.ts', '.tsx' ]
	},
	module: {
		rules: [
			{
				test: /\.jsx?$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader'
				}
			},
			{
				test: /\.(j|t)sx?$/,
				exclude: [ /node_modules/ ],
				use: require.resolve( 'source-map-loader' ),
				enforce: 'pre'
			},
			{
				test: /\.(css|scss)$/,
				use: [
					{
						loader: MiniCssExtractPlugin.loader
					},
					{
						loader: require.resolve( 'css-loader' ),
						options: {
							importLoaders: 1,
							sourceMap: true,
							modules: {
								auto: true
							}
						}
					},
					{
						loader: 'sass-loader',
						options: {
							implementation: require( 'sass' ),
							sourceMap: true
						}
					},
					{
						loader: require.resolve( 'postcss-loader' ),
						options: {
							postcssOptions: {
								ident: 'postcss',
								sourceMap: ! isProduction,
								plugins: isProduction ? [
									...postcssPlugins,
									require( 'cssnano' )( {
										preset: [
											'default',
											{
												discardComments: {
													removeAll: true
												}
											}
										]
									} )
								] : postcssPlugins
							}
						}
					}
				]
			},
			{
				test: /\.svg$/,
				issuer: /\.(j|t)sx?$/,
				use: [ '@svgr/webpack', 'url-loader' ],
				type: 'javascript/auto'
			},
			{
				test: /\.svg$/,
				issuer: /\.(pc|sc|sa|c)ss$/,
				type: 'asset/inline'
			},
			{
				test: /\.(bmp|png|jpe?g|gif|webp)$/i,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name].[hash:8][ext]'
				}
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/i,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name].[hash:8][ext]'
				}
			}
		]
	},
	plugins: [
		new webpack.DefinePlugin( {
			APP_VERSION: JSON.stringify( require( './package.json' ).version )
		} ),
		new MiniCssExtractPlugin( {
			filename: 'css/[name].min.css',
			chunkFilename: '[id].css',
			ignoreOrder: false
		} ),
		new RtlCssPlugin( {
			filename: 'css/[name]-rtl.css'
		} ),
		new RemoveEmptyScriptsPlugin(),
		new DependencyExtractionWebpackPlugin(),
		sentryWebpackPlugin( {
			org: process.env.SENTRY_ORG,
			project: process.env.SENTRY_PROJECT,
			authToken: process.env.SENTRY_AUTH_TOKEN
		} )
	]
}

const admin = {
	...config,
	entry: {
		'admin-orders-page': [
			'./admin/assets/src/js/admin-orders-page.js',
			'./admin/assets/src/scss/admin-orders-page.scss'
		],
	},
	output: {
		filename: 'js/[name].min.js',
		path: resolve( __dirname, 'admin/assets/dist/' )
	},
	resolve: {
		...config.resolve,
		alias: {
			...( config.resolve ? config.resolve.alias : {} ),
			'@': resolve( __dirname, 'admin/assets/src/js' )
		}
	}
}

module.exports = [
	admin
]
