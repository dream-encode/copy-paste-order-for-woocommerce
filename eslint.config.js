import js from '@eslint/js'
import globals from 'globals'
import reactPlugin from 'eslint-plugin-react'

export default [
	js.configs.recommended,
	{
		files: [ '**/*.{js,jsx,ts,tsx}' ],
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'module',
			globals: {
				...globals.browser,
				...globals.node,
				wp: 'readonly',
				jQuery: 'readonly',
				$: 'readonly',
				CPOFW: 'readonly'
			},
			parserOptions: {
				ecmaFeatures: {
					jsx: true
				}
			}
		},
		plugins: {
			react: reactPlugin
		},
		rules: {
			...reactPlugin.configs.recommended.rules,
			'react/react-in-jsx-scope': 'off',
			'react/prop-types': 'off',
			'no-unused-vars': [ 'error', { argsIgnorePattern: '^_' } ],
			'no-console': 'warn'
		},
		settings: {
			react: {
				version: 'detect'
			}
		}
	}
]
