import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Ant Design memakai system font stack, bukan webfont dekoratif.
                sans: [
                    '-apple-system',
                    'BlinkMacSystemFont',
                    'Segoe UI',
                    'Roboto',
                    'Helvetica Neue',
                    'Arial',
                    'Noto Sans',
                    'sans-serif',
                    ...defaultTheme.fontFamily.sans,
                ],
            },

            // ── Palet Ant Design v5 (seed token #1677ff) ──────────────────────
            colors: {
                primary: {
                    DEFAULT: '#1677ff',
                    50: '#e6f4ff',
                    100: '#bae0ff',
                    200: '#91caff',
                    300: '#69b1ff',
                    400: '#4096ff',
                    500: '#1677ff',
                    600: '#0958d9',
                    700: '#003eb3',
                    800: '#002c8c',
                    900: '#001d66',
                },
                success: {
                    DEFAULT: '#52c41a',
                    50: '#f6ffed',
                    100: '#d9f7be',
                    200: '#b7eb8f',
                    300: '#95de64',
                    400: '#73d13d',
                    500: '#52c41a',
                    600: '#389e0d',
                    700: '#237804',
                },
                warning: {
                    DEFAULT: '#faad14',
                    50: '#fffbe6',
                    100: '#fff1b8',
                    200: '#ffe58f',
                    300: '#ffd666',
                    400: '#ffc53d',
                    500: '#faad14',
                    600: '#d48806',
                    700: '#ad6800',
                },
                danger: {
                    DEFAULT: '#ff4d4f',
                    50: '#fff2f0',
                    100: '#ffccc7',
                    200: '#ffa39e',
                    300: '#ff7875',
                    400: '#ff4d4f',
                    500: '#ff4d4f',
                    600: '#d9363e',
                    700: '#a8071a',
                },
                // Skala netral khas Ant Design (layout, border, tipografi).
                ant: {
                    title: '#262626',
                    text: '#595959',
                    secondary: '#8c8c8c',
                    disabled: '#bfbfbf',
                    border: '#d9d9d9',
                    split: '#f0f0f0',
                    layout: '#f5f5f5',
                    container: '#ffffff',
                },
            },

            borderRadius: {
                ant: '6px',
                'ant-sm': '4px',
                'ant-lg': '8px',
            },

            boxShadow: {
                'ant-card':
                    '0 1px 2px 0 rgba(0,0,0,0.03), 0 1px 6px -1px rgba(0,0,0,0.02), 0 2px 4px 0 rgba(0,0,0,0.02)',
                'ant-dropdown':
                    '0 6px 16px 0 rgba(0,0,0,0.08), 0 3px 6px -4px rgba(0,0,0,0.12), 0 9px 28px 8px rgba(0,0,0,0.05)',
                'ant-focus': '0 0 0 2px rgba(5,145,255,0.10)',
            },
        },
    },

    plugins: [forms],
};
