<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>

    <title>Espelho OC</title>

    <style>
        @charset "UTF-8";
        dl, h1, h2, h3, h4, h5, h6, ol, p, pre, ul {
            margin-top: 0
        }

        address, dl, ol, p, pre, ul {
            margin-bottom: 1rem
        }

        img, svg {
            vertical-align: middle
        }

        body, caption {
            text-align: left
        }

        dd, h1, h2, h3, h4, h5, h6, label {
            margin-bottom: .5rem
        }

        pre, textarea {
            overflow: auto
        }

        article, aside, figcaption, figure, footer, header, hgroup, legend, main, nav, section {
            display: block
        }

        address, legend {
            line-height: inherit
        }

        a:not([href]):not([tabindex]), a:not([href]):not([tabindex]):focus, a:not([href]):not([tabindex]):hover, legend {
            color: inherit
        }

        progress, sub, sup {
            vertical-align: baseline
        }

        label, output {
            display: inline-block
        }

        button, hr, input {
            overflow: visible
        }

        .btn-group-vertical, .form-inline, .navbar-expand, .navbar-nav {
            -webkit-box-direction: normal
        }

        .btn, .data-table table th {
            -moz-user-select: none;
            -ms-user-select: none
        }

        .breadcrumb, .dropdown-menu, .icon-list, .list, .list-inline, .list-unstyled, .nav, .navbar-nav, .navigation, .navigation__sub > ul, .pagination, .price-table__info, .top-menu, .top-nav {
            list-style: none
        }

        .waves-effect, html {
            -webkit-tap-highlight-color: transparent
        }

        :root {
            --gray: #868e96;
            --gray-dark: #343a40;
            --white: #FFFFFF;
            --black: #000000;
            --red: #ff6b68;
            --pink: #ff85af;
            --purple: #d066e2;
            --deep-purple: #673AB7;
            --indigo: #3F51B5;
            --blue: #2196F3;
            --light-blue: #03A9F4;
            --cyan: #00BCD4;
            --teal: #39bbb0;
            --green: #32c787;
            --light-green: #8BC34A;
            --lime: #CDDC39;
            --yellow: #FFEB3B;
            --amber: #ffc721;
            --orange: #FF9800;
            --deep-orange: #FF5722;
            --brown: #795548;
            --blue-grey: #607D8B;
            --primary: #2196F3;
            --secondary: #868e96;
            --success: #32c787;
            --info: #03A9F4;
            --warning: #ffc721;
            --danger: #ff6b68;
            --light: #f6f6f6;
            --dark: #495057;
            --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --font-family-sans-serif: "Roboto", sans-serif;
            --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace
        }

        *, ::after, ::before {
            box-sizing: border-box
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0;
            font-family: Roboto, sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #747a80;
            background-color: #f3f3f3
        }

        [tabindex="-1"]:focus {
            outline: 0 !important
        }

        abbr[data-original-title], abbr[title] {
            text-decoration: underline;
            text-decoration: underline dotted;
            cursor: help;
            border-bottom: 0;
            text-decoration-skip-ink: none
        }

        .breadcrumb-item + .breadcrumb-item:hover::before, .btn-link.focus, .btn-link:focus, .btn-link:hover, .btn:hover, .card-link:hover, .dropdown-item:focus, .dropdown-item:hover, .nav-link:focus, .nav-link:hover, .navbar-brand:focus, .navbar-brand:hover, .navbar-toggler:focus, .navbar-toggler:hover, .page-link:hover, a, a.badge:focus, a.badge:hover, a:hover, a:not([href]):not([tabindex]), a:not([href]):not([tabindex]):focus, a:not([href]):not([tabindex]):hover {
            text-decoration: none
        }

        .btn:not(:disabled):not(.disabled), summary {
            cursor: pointer
        }

        address {
            font-style: normal
        }

        ol ol, ol ul, ul ol, ul ul {
            margin-bottom: 0
        }

        dt {
            font-weight: 500
        }

        dd {
            margin-left: 0
        }

        blockquote, figure {
            margin: 0 0 1rem
        }

        small {
            font-size: 80%
        }

        sub, sup {
            position: relative;
            font-size: 75%;
            line-height: 0
        }

        sub {
            bottom: -.25em
        }

        sup {
            top: -.5em
        }

        a {
            color: #2196F3;
            background-color: transparent
        }

        a:hover {
            color: #0a6ebd
        }

        a:not([href]):not([tabindex]):focus {
            outline: 0
        }

        code, kbd, pre, samp {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 1em
        }

        img {
            border-style: none
        }

        svg {
            overflow: hidden
        }

        table {
            border-collapse: collapse
        }

        caption {
            padding-top: 1rem 1.5rem;
            padding-bottom: 1rem 1.5rem;
            color: #9c9c9c;
            caption-side: bottom
        }

        th {
            text-align: inherit
        }

        button {
            border-radius: 0
        }

        button:focus {
            outline: dotted 1px;
            outline: -webkit-focus-ring-color auto 5px
        }

        button, input, optgroup, select, textarea {
            margin: 0;
            font-size: inherit;
            line-height: inherit
        }

        button, select {
            text-transform: none
        }

        [type=button], [type=reset], [type=submit], button {
            -webkit-appearance: button
        }

        [type=button]::-moz-focus-inner, [type=reset]::-moz-focus-inner, [type=submit]::-moz-focus-inner, button::-moz-focus-inner {
            padding: 0;
            border-style: none
        }

        input[type=radio], input[type=checkbox] {
            box-sizing: border-box;
            padding: 0
        }

        input[type=date], input[type=time], input[type=datetime-local], input[type=month] {
            -webkit-appearance: listbox
        }

        textarea {
            resize: vertical
        }

        fieldset {
            min-width: 0;
            padding: 0;
            margin: 0;
            border: 0
        }

        legend {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin-bottom: .5rem;
            font-size: 1.5rem;
            white-space: normal
        }

        [type=number]::-webkit-inner-spin-button, [type=number]::-webkit-outer-spin-button {
            height: auto
        }

        [type=search] {
            outline-offset: -2px;
            -webkit-appearance: none
        }

        [type=search]::-webkit-search-decoration {
            -webkit-appearance: none
        }

        ::-webkit-file-upload-button {
            font: inherit;
            -webkit-appearance: button
        }

        .display-1, .display-2, .display-3, .display-4 {
            line-height: 1.2
        }

        summary {
            display: list-item
        }

        template {
            display: none
        }

        [hidden] {
            display: none !important
        }

        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            margin-bottom: .5rem;
            font-family: inherit;
            font-weight: 500;
            line-height: 1.2;
            color: #333
        }

        .blockquote, hr {
            margin-bottom: 1rem
        }

        .display-1, .display-2, .display-3, .display-4, .lead {
            font-weight: 300
        }

        .h1, h1 {
            font-size: 2.5rem
        }

        .h2, h2 {
            font-size: 2rem
        }

        .h3, h3 {
            font-size: 1.75rem
        }

        .h4, h4 {
            font-size: 1.5rem
        }

        .h5, h5 {
            font-size: 1.25rem
        }

        .h6, h6 {
            font-size: 1rem
        }

        .lead {
            font-size: 1.25rem
        }

        .display-1 {
            font-size: 6rem
        }

        .display-2 {
            font-size: 5.5rem
        }

        .display-3 {
            font-size: 4.5rem
        }

        .display-4 {
            font-size: 3.5rem
        }

        hr {
            box-sizing: content-box;
            height: 0;
            margin-top: 1rem;
            border: 0;
            border-top: 1px solid #e9ecef
        }

        .img-fluid, .img-thumbnail {
            max-width: 100%;
            height: auto
        }

        .small, small {
            font-size: 80%;
            font-weight: 400
        }

        .mark, mark {
            padding: .2em;
            background-color: #fcf8e3
        }

        .list-inline, .list-unstyled {
            padding-left: 0
        }

        .list-inline-item {
            display: inline-block
        }

        .list-inline-item:not(:last-child) {
            margin-right: .5rem
        }

        .initialism {
            font-size: 90%;
            text-transform: uppercase
        }

        .blockquote {
            font-size: 1.25rem
        }

        .blockquote-footer {
            display: block;
            font-size: 80%;
            color: #868e96
        }

        .blockquote-footer::before {
            content: "\2014\00A0"
        }

        .img-thumbnail {
            padding: .25rem;
            background-color: #f3f3f3;
            border: 1px solid #dee2e6;
            border-radius: 2px
        }

        .figure {
            display: inline-block
        }

        .figure-img {
            margin-bottom: .5rem;
            line-height: 1
        }

        .figure-caption {
            font-size: 90%;
            color: #868e96
        }

        code, kbd {
            font-size: 87.5%
        }

        a > code, pre code {
            color: inherit
        }

        code {
            color: #ff85af;
            word-break: break-word
        }

        kbd, pre {
            color: #FFF
        }

        kbd {
            padding: .2rem .4rem;
            background-color: #212529;
            border-radius: 2px
        }

        kbd kbd {
            padding: 0;
            font-size: 100%;
            font-weight: 500
        }

        .container, .container-fluid {
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            width: 100%
        }

        .btn, .btn-link, .custom-select, .dropdown-item, .form-control, .input-group-text {
            font-weight: 400
        }

        pre {
            display: block;
            font-size: 87.5%
        }

        pre code {
            font-size: inherit;
            word-break: normal
        }

        .pre-scrollable {
            max-height: 340px;
            overflow-y: scroll
        }

        @media (min-width: 576px) {
            .container {
                max-width: 540px
            }
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px
            }
        }

        @media (min-width: 992px) {
            .container {
                max-width: 960px
            }
        }

        @media (min-width: 1200px) {
            .container {
                max-width: 1140px
            }
        }

        .col, .col-auto {
            max-width: 100%
        }

        .row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px
        }

        .no-gutters {
            margin-right: 0;
            margin-left: 0
        }

        .no-gutters > .col, .no-gutters > [class*=col-] {
            padding-right: 0;
            padding-left: 0
        }

        .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px
        }

        .col {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .col-1, .col-auto {
            -webkit-box-flex: 0
        }

        .col-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto
        }

        .col-1 {
            -ms-flex: 0 0 8.3333333333%;
            flex: 0 0 8.3333333333%;
            max-width: 8.3333333333%
        }

        .col-2, .col-3 {
            -webkit-box-flex: 0
        }

        .col-2 {
            -ms-flex: 0 0 16.6666666667%;
            flex: 0 0 16.6666666667%;
            max-width: 16.6666666667%
        }

        .col-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%
        }

        .col-4, .col-5 {
            -webkit-box-flex: 0
        }

        .col-4 {
            -ms-flex: 0 0 33.3333333333%;
            flex: 0 0 33.3333333333%;
            max-width: 33.3333333333%
        }

        .col-5 {
            -ms-flex: 0 0 41.6666666667%;
            flex: 0 0 41.6666666667%;
            max-width: 41.6666666667%
        }

        .col-6, .col-7 {
            -webkit-box-flex: 0
        }

        .col-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%
        }

        .col-7 {
            -ms-flex: 0 0 58.3333333333%;
            flex: 0 0 58.3333333333%;
            max-width: 58.3333333333%
        }

        .col-8, .col-9 {
            -webkit-box-flex: 0
        }

        .col-8 {
            -ms-flex: 0 0 66.6666666667%;
            flex: 0 0 66.6666666667%;
            max-width: 66.6666666667%
        }

        .col-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%
        }

        .col-10, .col-11 {
            -webkit-box-flex: 0
        }

        .col-10 {
            -ms-flex: 0 0 83.3333333333%;
            flex: 0 0 83.3333333333%;
            max-width: 83.3333333333%
        }

        .col-11 {
            -ms-flex: 0 0 91.6666666667%;
            flex: 0 0 91.6666666667%;
            max-width: 91.6666666667%
        }

        .col-12 {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%
        }

        .order-first {
            -webkit-box-ordinal-group: 0;
            -ms-flex-order: -1;
            order: -1
        }

        .order-last {
            -webkit-box-ordinal-group: 14;
            -ms-flex-order: 13;
            order: 13
        }

        .order-0 {
            -webkit-box-ordinal-group: 1;
            -ms-flex-order: 0;
            order: 0
        }

        .order-1 {
            -webkit-box-ordinal-group: 2;
            -ms-flex-order: 1;
            order: 1
        }

        .order-2 {
            -webkit-box-ordinal-group: 3;
            -ms-flex-order: 2;
            order: 2
        }

        .order-3 {
            -webkit-box-ordinal-group: 4;
            -ms-flex-order: 3;
            order: 3
        }

        .order-4 {
            -webkit-box-ordinal-group: 5;
            -ms-flex-order: 4;
            order: 4
        }

        .order-5 {
            -webkit-box-ordinal-group: 6;
            -ms-flex-order: 5;
            order: 5
        }

        .order-6 {
            -webkit-box-ordinal-group: 7;
            -ms-flex-order: 6;
            order: 6
        }

        .order-7 {
            -webkit-box-ordinal-group: 8;
            -ms-flex-order: 7;
            order: 7
        }

        .order-8 {
            -webkit-box-ordinal-group: 9;
            -ms-flex-order: 8;
            order: 8
        }

        .order-9 {
            -webkit-box-ordinal-group: 10;
            -ms-flex-order: 9;
            order: 9
        }

        .order-10 {
            -webkit-box-ordinal-group: 11;
            -ms-flex-order: 10;
            order: 10
        }

        .order-11 {
            -webkit-box-ordinal-group: 12;
            -ms-flex-order: 11;
            order: 11
        }

        .order-12 {
            -webkit-box-ordinal-group: 13;
            -ms-flex-order: 12;
            order: 12
        }

        .offset-1 {
            margin-left: 8.3333333333%
        }

        .offset-2 {
            margin-left: 16.6666666667%
        }

        .offset-3 {
            margin-left: 25%
        }

        .offset-4 {
            margin-left: 33.3333333333%
        }

        .offset-5 {
            margin-left: 41.6666666667%
        }

        .offset-6 {
            margin-left: 50%
        }

        .offset-7 {
            margin-left: 58.3333333333%
        }

        .offset-8 {
            margin-left: 66.6666666667%
        }

        .offset-9 {
            margin-left: 75%
        }

        .offset-10 {
            margin-left: 83.3333333333%
        }

        .offset-11 {
            margin-left: 91.6666666667%
        }

        @media (min-width: 576px) {
            .col-sm {
                -ms-flex-preferred-size: 0;
                flex-basis: 0;
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
                max-width: 100%
            }

            .col-sm-auto {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 auto;
                flex: 0 0 auto;
                width: auto;
                max-width: 100%
            }

            .col-sm-1 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 8.3333333333%;
                flex: 0 0 8.3333333333%;
                max-width: 8.3333333333%
            }

            .col-sm-2 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 16.6666666667%;
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%
            }

            .col-sm-3 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%
            }

            .col-sm-4 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 33.3333333333%;
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%
            }

            .col-sm-5 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 41.6666666667%;
                flex: 0 0 41.6666666667%;
                max-width: 41.6666666667%
            }

            .col-sm-6 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 50%;
                flex: 0 0 50%;
                max-width: 50%
            }

            .col-sm-7 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 58.3333333333%;
                flex: 0 0 58.3333333333%;
                max-width: 58.3333333333%
            }

            .col-sm-8 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 66.6666666667%;
                flex: 0 0 66.6666666667%;
                max-width: 66.6666666667%
            }

            .col-sm-9 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 75%;
                flex: 0 0 75%;
                max-width: 75%
            }

            .col-sm-10 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 83.3333333333%;
                flex: 0 0 83.3333333333%;
                max-width: 83.3333333333%
            }

            .col-sm-11 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 91.6666666667%;
                flex: 0 0 91.6666666667%;
                max-width: 91.6666666667%
            }

            .col-sm-12 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%
            }

            .order-sm-first {
                -webkit-box-ordinal-group: 0;
                -ms-flex-order: -1;
                order: -1
            }

            .order-sm-last {
                -webkit-box-ordinal-group: 14;
                -ms-flex-order: 13;
                order: 13
            }

            .order-sm-0 {
                -webkit-box-ordinal-group: 1;
                -ms-flex-order: 0;
                order: 0
            }

            .order-sm-1 {
                -webkit-box-ordinal-group: 2;
                -ms-flex-order: 1;
                order: 1
            }

            .order-sm-2 {
                -webkit-box-ordinal-group: 3;
                -ms-flex-order: 2;
                order: 2
            }

            .order-sm-3 {
                -webkit-box-ordinal-group: 4;
                -ms-flex-order: 3;
                order: 3
            }

            .order-sm-4 {
                -webkit-box-ordinal-group: 5;
                -ms-flex-order: 4;
                order: 4
            }

            .order-sm-5 {
                -webkit-box-ordinal-group: 6;
                -ms-flex-order: 5;
                order: 5
            }

            .order-sm-6 {
                -webkit-box-ordinal-group: 7;
                -ms-flex-order: 6;
                order: 6
            }

            .order-sm-7 {
                -webkit-box-ordinal-group: 8;
                -ms-flex-order: 7;
                order: 7
            }

            .order-sm-8 {
                -webkit-box-ordinal-group: 9;
                -ms-flex-order: 8;
                order: 8
            }

            .order-sm-9 {
                -webkit-box-ordinal-group: 10;
                -ms-flex-order: 9;
                order: 9
            }

            .order-sm-10 {
                -webkit-box-ordinal-group: 11;
                -ms-flex-order: 10;
                order: 10
            }

            .order-sm-11 {
                -webkit-box-ordinal-group: 12;
                -ms-flex-order: 11;
                order: 11
            }

            .order-sm-12 {
                -webkit-box-ordinal-group: 13;
                -ms-flex-order: 12;
                order: 12
            }

            .offset-sm-0 {
                margin-left: 0
            }

            .offset-sm-1 {
                margin-left: 8.3333333333%
            }

            .offset-sm-2 {
                margin-left: 16.6666666667%
            }

            .offset-sm-3 {
                margin-left: 25%
            }

            .offset-sm-4 {
                margin-left: 33.3333333333%
            }

            .offset-sm-5 {
                margin-left: 41.6666666667%
            }

            .offset-sm-6 {
                margin-left: 50%
            }

            .offset-sm-7 {
                margin-left: 58.3333333333%
            }

            .offset-sm-8 {
                margin-left: 66.6666666667%
            }

            .offset-sm-9 {
                margin-left: 75%
            }

            .offset-sm-10 {
                margin-left: 83.3333333333%
            }

            .offset-sm-11 {
                margin-left: 91.6666666667%
            }
        }

        @media (min-width: 768px) {
            .col-md {
                -ms-flex-preferred-size: 0;
                flex-basis: 0;
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
                max-width: 100%
            }

            .col-md-auto {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 auto;
                flex: 0 0 auto;
                width: auto;
                max-width: 100%
            }

            .col-md-1 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 8.3333333333%;
                flex: 0 0 8.3333333333%;
                max-width: 8.3333333333%
            }

            .col-md-2 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 16.6666666667%;
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%
            }

            .col-md-3 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%
            }

            .col-md-4 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 33.3333333333%;
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%
            }

            .col-md-5 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 41.6666666667%;
                flex: 0 0 41.6666666667%;
                max-width: 41.6666666667%
            }

            .col-md-6 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 50%;
                flex: 0 0 50%;
                max-width: 50%
            }

            .col-md-7 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 58.3333333333%;
                flex: 0 0 58.3333333333%;
                max-width: 58.3333333333%
            }

            .col-md-8 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 66.6666666667%;
                flex: 0 0 66.6666666667%;
                max-width: 66.6666666667%
            }

            .col-md-9 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 75%;
                flex: 0 0 75%;
                max-width: 75%
            }

            .col-md-10 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 83.3333333333%;
                flex: 0 0 83.3333333333%;
                max-width: 83.3333333333%
            }

            .col-md-11 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 91.6666666667%;
                flex: 0 0 91.6666666667%;
                max-width: 91.6666666667%
            }

            .col-md-12 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%
            }

            .order-md-first {
                -webkit-box-ordinal-group: 0;
                -ms-flex-order: -1;
                order: -1
            }

            .order-md-last {
                -webkit-box-ordinal-group: 14;
                -ms-flex-order: 13;
                order: 13
            }

            .order-md-0 {
                -webkit-box-ordinal-group: 1;
                -ms-flex-order: 0;
                order: 0
            }

            .order-md-1 {
                -webkit-box-ordinal-group: 2;
                -ms-flex-order: 1;
                order: 1
            }

            .order-md-2 {
                -webkit-box-ordinal-group: 3;
                -ms-flex-order: 2;
                order: 2
            }

            .order-md-3 {
                -webkit-box-ordinal-group: 4;
                -ms-flex-order: 3;
                order: 3
            }

            .order-md-4 {
                -webkit-box-ordinal-group: 5;
                -ms-flex-order: 4;
                order: 4
            }

            .order-md-5 {
                -webkit-box-ordinal-group: 6;
                -ms-flex-order: 5;
                order: 5
            }

            .order-md-6 {
                -webkit-box-ordinal-group: 7;
                -ms-flex-order: 6;
                order: 6
            }

            .order-md-7 {
                -webkit-box-ordinal-group: 8;
                -ms-flex-order: 7;
                order: 7
            }

            .order-md-8 {
                -webkit-box-ordinal-group: 9;
                -ms-flex-order: 8;
                order: 8
            }

            .order-md-9 {
                -webkit-box-ordinal-group: 10;
                -ms-flex-order: 9;
                order: 9
            }

            .order-md-10 {
                -webkit-box-ordinal-group: 11;
                -ms-flex-order: 10;
                order: 10
            }

            .order-md-11 {
                -webkit-box-ordinal-group: 12;
                -ms-flex-order: 11;
                order: 11
            }

            .order-md-12 {
                -webkit-box-ordinal-group: 13;
                -ms-flex-order: 12;
                order: 12
            }

            .offset-md-0 {
                margin-left: 0
            }

            .offset-md-1 {
                margin-left: 8.3333333333%
            }

            .offset-md-2 {
                margin-left: 16.6666666667%
            }

            .offset-md-3 {
                margin-left: 25%
            }

            .offset-md-4 {
                margin-left: 33.3333333333%
            }

            .offset-md-5 {
                margin-left: 41.6666666667%
            }

            .offset-md-6 {
                margin-left: 50%
            }

            .offset-md-7 {
                margin-left: 58.3333333333%
            }

            .offset-md-8 {
                margin-left: 66.6666666667%
            }

            .offset-md-9 {
                margin-left: 75%
            }

            .offset-md-10 {
                margin-left: 83.3333333333%
            }

            .offset-md-11 {
                margin-left: 91.6666666667%
            }
        }

        @media (min-width: 992px) {
            .col-lg {
                -ms-flex-preferred-size: 0;
                flex-basis: 0;
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
                max-width: 100%
            }

            .col-lg-auto {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 auto;
                flex: 0 0 auto;
                width: auto;
                max-width: 100%
            }

            .col-lg-1 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 8.3333333333%;
                flex: 0 0 8.3333333333%;
                max-width: 8.3333333333%
            }

            .col-lg-2 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 16.6666666667%;
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%
            }

            .col-lg-3 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%
            }

            .col-lg-4 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 33.3333333333%;
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%
            }

            .col-lg-5 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 41.6666666667%;
                flex: 0 0 41.6666666667%;
                max-width: 41.6666666667%
            }

            .col-lg-6 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 50%;
                flex: 0 0 50%;
                max-width: 50%
            }

            .col-lg-7 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 58.3333333333%;
                flex: 0 0 58.3333333333%;
                max-width: 58.3333333333%
            }

            .col-lg-8 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 66.6666666667%;
                flex: 0 0 66.6666666667%;
                max-width: 66.6666666667%
            }

            .col-lg-9 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 75%;
                flex: 0 0 75%;
                max-width: 75%
            }

            .col-lg-10 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 83.3333333333%;
                flex: 0 0 83.3333333333%;
                max-width: 83.3333333333%
            }

            .col-lg-11 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 91.6666666667%;
                flex: 0 0 91.6666666667%;
                max-width: 91.6666666667%
            }

            .col-lg-12 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%
            }

            .order-lg-first {
                -webkit-box-ordinal-group: 0;
                -ms-flex-order: -1;
                order: -1
            }

            .order-lg-last {
                -webkit-box-ordinal-group: 14;
                -ms-flex-order: 13;
                order: 13
            }

            .order-lg-0 {
                -webkit-box-ordinal-group: 1;
                -ms-flex-order: 0;
                order: 0
            }

            .order-lg-1 {
                -webkit-box-ordinal-group: 2;
                -ms-flex-order: 1;
                order: 1
            }

            .order-lg-2 {
                -webkit-box-ordinal-group: 3;
                -ms-flex-order: 2;
                order: 2
            }

            .order-lg-3 {
                -webkit-box-ordinal-group: 4;
                -ms-flex-order: 3;
                order: 3
            }

            .order-lg-4 {
                -webkit-box-ordinal-group: 5;
                -ms-flex-order: 4;
                order: 4
            }

            .order-lg-5 {
                -webkit-box-ordinal-group: 6;
                -ms-flex-order: 5;
                order: 5
            }

            .order-lg-6 {
                -webkit-box-ordinal-group: 7;
                -ms-flex-order: 6;
                order: 6
            }

            .order-lg-7 {
                -webkit-box-ordinal-group: 8;
                -ms-flex-order: 7;
                order: 7
            }

            .order-lg-8 {
                -webkit-box-ordinal-group: 9;
                -ms-flex-order: 8;
                order: 8
            }

            .order-lg-9 {
                -webkit-box-ordinal-group: 10;
                -ms-flex-order: 9;
                order: 9
            }

            .order-lg-10 {
                -webkit-box-ordinal-group: 11;
                -ms-flex-order: 10;
                order: 10
            }

            .order-lg-11 {
                -webkit-box-ordinal-group: 12;
                -ms-flex-order: 11;
                order: 11
            }

            .order-lg-12 {
                -webkit-box-ordinal-group: 13;
                -ms-flex-order: 12;
                order: 12
            }

            .offset-lg-0 {
                margin-left: 0
            }

            .offset-lg-1 {
                margin-left: 8.3333333333%
            }

            .offset-lg-2 {
                margin-left: 16.6666666667%
            }

            .offset-lg-3 {
                margin-left: 25%
            }

            .offset-lg-4 {
                margin-left: 33.3333333333%
            }

            .offset-lg-5 {
                margin-left: 41.6666666667%
            }

            .offset-lg-6 {
                margin-left: 50%
            }

            .offset-lg-7 {
                margin-left: 58.3333333333%
            }

            .offset-lg-8 {
                margin-left: 66.6666666667%
            }

            .offset-lg-9 {
                margin-left: 75%
            }

            .offset-lg-10 {
                margin-left: 83.3333333333%
            }

            .offset-lg-11 {
                margin-left: 91.6666666667%
            }
        }

        @media (min-width: 1200px) {
            .col-xl {
                -ms-flex-preferred-size: 0;
                flex-basis: 0;
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1;
                max-width: 100%
            }

            .col-xl-auto {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 auto;
                flex: 0 0 auto;
                width: auto;
                max-width: 100%
            }

            .col-xl-1 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 8.3333333333%;
                flex: 0 0 8.3333333333%;
                max-width: 8.3333333333%
            }

            .col-xl-2 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 16.6666666667%;
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%
            }

            .col-xl-3 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%
            }

            .col-xl-4 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 33.3333333333%;
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%
            }

            .col-xl-5 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 41.6666666667%;
                flex: 0 0 41.6666666667%;
                max-width: 41.6666666667%
            }

            .col-xl-6 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 50%;
                flex: 0 0 50%;
                max-width: 50%
            }

            .col-xl-7 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 58.3333333333%;
                flex: 0 0 58.3333333333%;
                max-width: 58.3333333333%
            }

            .col-xl-8 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 66.6666666667%;
                flex: 0 0 66.6666666667%;
                max-width: 66.6666666667%
            }

            .col-xl-9 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 75%;
                flex: 0 0 75%;
                max-width: 75%
            }

            .col-xl-10 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 83.3333333333%;
                flex: 0 0 83.3333333333%;
                max-width: 83.3333333333%
            }

            .col-xl-11 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 91.6666666667%;
                flex: 0 0 91.6666666667%;
                max-width: 91.6666666667%
            }

            .col-xl-12 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 100%;
                flex: 0 0 100%;
                max-width: 100%
            }

            .order-xl-first {
                -webkit-box-ordinal-group: 0;
                -ms-flex-order: -1;
                order: -1
            }

            .order-xl-last {
                -webkit-box-ordinal-group: 14;
                -ms-flex-order: 13;
                order: 13
            }

            .order-xl-0 {
                -webkit-box-ordinal-group: 1;
                -ms-flex-order: 0;
                order: 0
            }

            .order-xl-1 {
                -webkit-box-ordinal-group: 2;
                -ms-flex-order: 1;
                order: 1
            }

            .order-xl-2 {
                -webkit-box-ordinal-group: 3;
                -ms-flex-order: 2;
                order: 2
            }

            .order-xl-3 {
                -webkit-box-ordinal-group: 4;
                -ms-flex-order: 3;
                order: 3
            }

            .order-xl-4 {
                -webkit-box-ordinal-group: 5;
                -ms-flex-order: 4;
                order: 4
            }

            .order-xl-5 {
                -webkit-box-ordinal-group: 6;
                -ms-flex-order: 5;
                order: 5
            }

            .order-xl-6 {
                -webkit-box-ordinal-group: 7;
                -ms-flex-order: 6;
                order: 6
            }

            .order-xl-7 {
                -webkit-box-ordinal-group: 8;
                -ms-flex-order: 7;
                order: 7
            }

            .order-xl-8 {
                -webkit-box-ordinal-group: 9;
                -ms-flex-order: 8;
                order: 8
            }

            .order-xl-9 {
                -webkit-box-ordinal-group: 10;
                -ms-flex-order: 9;
                order: 9
            }

            .order-xl-10 {
                -webkit-box-ordinal-group: 11;
                -ms-flex-order: 10;
                order: 10
            }

            .order-xl-11 {
                -webkit-box-ordinal-group: 12;
                -ms-flex-order: 11;
                order: 11
            }

            .order-xl-12 {
                -webkit-box-ordinal-group: 13;
                -ms-flex-order: 12;
                order: 12
            }

            .offset-xl-0 {
                margin-left: 0
            }

            .offset-xl-1 {
                margin-left: 8.3333333333%
            }

            .offset-xl-2 {
                margin-left: 16.6666666667%
            }

            .offset-xl-3 {
                margin-left: 25%
            }

            .offset-xl-4 {
                margin-left: 33.3333333333%
            }

            .offset-xl-5 {
                margin-left: 41.6666666667%
            }

            .offset-xl-6 {
                margin-left: 50%
            }

            .offset-xl-7 {
                margin-left: 58.3333333333%
            }

            .offset-xl-8 {
                margin-left: 66.6666666667%
            }

            .offset-xl-9 {
                margin-left: 75%
            }

            .offset-xl-10 {
                margin-left: 83.3333333333%
            }

            .offset-xl-11 {
                margin-left: 91.6666666667%
            }
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent
        }

        .table td, .table th {
            padding: 1rem 1.5rem;
            vertical-align: top;
            border-top: 1px solid #f2f4f5
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #f2f4f5
        }

        .table tbody + tbody {
            border-top: 2px solid #f2f4f5
        }

        .table .table {
            background-color: #f3f3f3
        }

        .table-sm td, .table-sm th {
            padding: .75rem 1rem
        }

        .table-bordered, .table-bordered td, .table-bordered th {
            border: 1px solid #f2f4f5
        }

        .table-bordered thead td, .table-bordered thead th {
            border-bottom-width: 2px
        }

        .table-borderless tbody + tbody, .table-borderless td, .table-borderless th, .table-borderless thead th {
            border: 0
        }

        .table-hover tbody tr:hover, .table-striped tbody tr:nth-of-type(odd) {
            background-color: #ebebeb !important;
        }

        .table-primary, .table-primary > td, .table-primary > th {
            background-color: #c1e2fc
        }

        .table-primary tbody + tbody, .table-primary td, .table-primary th, .table-primary thead th {
            border-color: #8cc8f9
        }

        .table-hover .table-primary:hover, .table-hover .table-primary:hover > td, .table-hover .table-primary:hover > th {
            background-color: #a9d7fb
        }

        .table-secondary, .table-secondary > td, .table-secondary > th {
            background-color: #dddfe2
        }

        .table-secondary tbody + tbody, .table-secondary td, .table-secondary th, .table-secondary thead th {
            border-color: #c0c4c8
        }

        .table-hover .table-secondary:hover, .table-hover .table-secondary:hover > td, .table-hover .table-secondary:hover > th {
            background-color: #cfd2d6
        }

        .table-success, .table-success > td, .table-success > th {
            background-color: #c6efdd
        }

        .table-success tbody + tbody, .table-success td, .table-success th, .table-success thead th {
            border-color: #94e2c1
        }

        .table-hover .table-success:hover, .table-hover .table-success:hover > td, .table-hover .table-success:hover > th {
            background-color: #b2e9d1
        }

        .table-info, .table-info > td, .table-info > th {
            background-color: #b8e7fc
        }

        .table-info tbody + tbody, .table-info td, .table-info th, .table-info thead th {
            border-color: #7cd2f9
        }

        .table-hover .table-info:hover, .table-hover .table-info:hover > td, .table-hover .table-info:hover > th {
            background-color: #a0dffb
        }

        .table-warning, .table-warning > td, .table-warning > th {
            background-color: #ffefc1
        }

        .table-warning tbody + tbody, .table-warning td, .table-warning th, .table-warning thead th {
            border-color: #ffe28c
        }

        .table-hover .table-warning:hover, .table-hover .table-warning:hover > td, .table-hover .table-warning:hover > th {
            background-color: #ffe8a8
        }

        .table-danger, .table-danger > td, .table-danger > th {
            background-color: #ffd6d5
        }

        .table-danger tbody + tbody, .table-danger td, .table-danger th, .table-danger thead th {
            border-color: #ffb2b0
        }

        .table-hover .table-danger:hover, .table-hover .table-danger:hover > td, .table-hover .table-danger:hover > th {
            background-color: #ffbdbc
        }

        .table-light, .table-light > td, .table-light > th {
            background-color: #fcfcfc
        }

        .table-light tbody + tbody, .table-light td, .table-light th, .table-light thead th {
            border-color: #fafafa
        }

        .table-hover .table-light:hover, .table-hover .table-light:hover > td, .table-hover .table-light:hover > th {
            background-color: #efefef
        }

        .table-dark, .table-dark > td, .table-dark > th {
            background-color: #ccced0
        }

        .table-dark tbody + tbody, .table-dark td, .table-dark th, .table-dark thead th {
            border-color: #a0a4a8
        }

        .table-hover .table-dark:hover, .table-hover .table-dark:hover > td, .table-hover .table-dark:hover > th {
            background-color: #bfc1c4
        }

        .table-active, .table-active > td, .table-active > th {
            background-color: #f2f4f5
        }

        .table-hover .table-active:hover, .table-hover .table-active:hover > td, .table-hover .table-active:hover > th {
            background-color: #e3e7eb
        }

        .table .thead-dark th {
            color: #f3f3f3;
            background-color: #313a44;
            border-color: #3e464e
        }

        .table .thead-light th {
            color: #495057;
            background-color: #f2f4f5;
            border-color: #f2f4f5
        }

        .table-dark {
            color: #f3f3f3;
            background-color: #313a44
        }

        .table-dark td, .table-dark th, .table-dark thead th {
            border-color: #3e464e
        }

        .table-dark.table-bordered, .table-responsive > .table-bordered {
            border: 0
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(255, 255, 255, .05)
        }

        .table-dark.table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, .075)
        }

        .form-control, .form-control-plaintext, .form-control:disabled, .form-control:focus, .form-control[readonly] {
            background-color: transparent
        }

        @media (max-width: 575.98px) {
            .table-responsive-sm {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar
            }

            .table-responsive-sm > .table-bordered {
                border: 0
            }
        }

        @media (max-width: 767.98px) {
            .table-responsive-md {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar
            }

            .table-responsive-md > .table-bordered {
                border: 0
            }
        }

        @media (max-width: 991.98px) {
            .table-responsive-lg {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar
            }

            .table-responsive-lg > .table-bordered {
                border: 0
            }
        }

        @media (max-width: 1199.98px) {
            .table-responsive-xl {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                -ms-overflow-style: -ms-autohiding-scrollbar
            }

            .table-responsive-xl > .table-bordered {
                border: 0
            }
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar
        }

        .accordion .card, .collapsing, .modal-open, .progress, .toast {
            overflow: hidden
        }

        .form-control {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem 0;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-clip: padding-box;
            border: 1px solid #eceff1;
            border-radius: 0;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out
        }

        .form-control::-ms-expand {
            background-color: transparent;
            border: 0
        }

        .form-control:focus {
            color: #495057;
            border-color: #eceff1;
            outline: 0;
            box-shadow: none
        }

        .form-control::-webkit-input-placeholder {
            color: #868e96;
            opacity: 1
        }

        .form-control:-ms-input-placeholder {
            color: #868e96;
            opacity: 1
        }

        .form-control::placeholder {
            color: #868e96;
            opacity: 1
        }

        select.form-control:focus::-ms-value {
            color: #495057;
            background-color: transparent
        }

        .form-control-file, .form-control-range {
            display: block;
            width: 100%
        }

        .col-form-label {
            padding-top: calc(.375rem + 1px);
            padding-bottom: calc(.375rem + 1px);
            margin-bottom: 0;
            font-size: inherit;
            line-height: 1.5
        }

        .col-form-label-lg {
            padding-top: calc(.5rem + 1px);
            padding-bottom: calc(.5rem + 1px);
            font-size: 1.25rem;
            line-height: 1.5
        }

        .col-form-label-sm {
            padding-top: calc(.25rem + 1px);
            padding-bottom: calc(.25rem + 1px);
            font-size: .875rem;
            line-height: 1.5
        }

        .form-control-plaintext {
            display: block;
            width: 100%;
            padding-top: .375rem;
            padding-bottom: .375rem;
            margin-bottom: 0;
            line-height: 1.5;
            color: #747a80;
            border: solid transparent;
            border-width: 1px 0
        }

        .form-control-plaintext.form-control-lg, .form-control-plaintext.form-control-sm {
            padding-right: 0;
            padding-left: 0
        }

        .form-control-sm {
            height: calc(1.8125rem + 2px);
            padding: .25rem 0;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 0
        }

        .form-control-lg {
            height: calc(2.875rem + 2px);
            padding: .5rem 0;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0
        }

        select.form-control[multiple], select.form-control[size], textarea.form-control {
            height: auto
        }

        .form-group {
            margin-bottom: 2rem
        }

        .form-text {
            display: block;
            margin-top: .25rem
        }

        .form-row {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -5px;
            margin-left: -5px
        }

        .form-row > .col, .form-row > [class*=col-] {
            padding-right: 5px;
            padding-left: 5px
        }

        .form-check {
            position: relative;
            display: block;
            padding-left: 1.25rem
        }

        .form-check-input {
            position: absolute;
            margin-top: .3rem;
            margin-left: -1.25rem
        }

        .form-check-input:disabled ~ .form-check-label {
            color: #9c9c9c
        }

        .form-check-label {
            margin-bottom: 0
        }

        .form-check-inline {
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding-left: 0;
            margin-right: .75rem
        }

        .form-check-inline .form-check-input {
            position: static;
            margin-top: 0;
            margin-right: .3125rem;
            margin-left: 0
        }

        .invalid-tooltip, .valid-tooltip {
            position: absolute;
            z-index: 5;
            max-width: 100%;
            top: 100%;
            line-height: 1.5
        }

        .valid-feedback {
            display: none;
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
            color: #32c787
        }

        .valid-tooltip {
            display: none;
            font-size: .875rem;
            color: #FFF;
            background-color: #32c787
        }

        .custom-control-input.is-valid ~ .valid-feedback, .custom-control-input.is-valid ~ .valid-tooltip, .custom-file-input.is-valid ~ .valid-feedback, .custom-file-input.is-valid ~ .valid-tooltip, .custom-select.is-valid ~ .valid-feedback, .custom-select.is-valid ~ .valid-tooltip, .form-check-input.is-valid ~ .valid-feedback, .form-check-input.is-valid ~ .valid-tooltip, .form-control-file.is-valid ~ .valid-feedback, .form-control-file.is-valid ~ .valid-tooltip, .form-control.is-valid ~ .valid-feedback, .form-control.is-valid ~ .valid-tooltip, .was-validated .custom-control-input:valid ~ .valid-feedback, .was-validated .custom-control-input:valid ~ .valid-tooltip, .was-validated .custom-file-input:valid ~ .valid-feedback, .was-validated .custom-file-input:valid ~ .valid-tooltip, .was-validated .custom-select:valid ~ .valid-feedback, .was-validated .custom-select:valid ~ .valid-tooltip, .was-validated .form-check-input:valid ~ .valid-feedback, .was-validated .form-check-input:valid ~ .valid-tooltip, .was-validated .form-control-file:valid ~ .valid-feedback, .was-validated .form-control-file:valid ~ .valid-tooltip, .was-validated .form-control:valid ~ .valid-feedback, .was-validated .form-control:valid ~ .valid-tooltip {
            display: block
        }

        .custom-control-input.is-valid ~ .custom-control-label, .form-check-input.is-valid ~ .form-check-label, .was-validated .custom-control-input:valid ~ .custom-control-label, .was-validated .form-check-input:valid ~ .form-check-label {
            color: #32c787
        }

        .form-control.is-valid, .was-validated .form-control:valid {
            border-color: #32c787;
            padding-right: 2.25rem;
            background-repeat: no-repeat;
            background-position: center right calc(2.25rem / 4);
            background-size: calc(2.25rem / 2) calc(2.25rem / 2);
            background-image: url(../img/forms/form-validation-valid.svg)
        }

        .form-control.is-valid:focus, .was-validated .form-control:valid:focus {
            border-color: #32c787;
            box-shadow: 0 0 0 .2rem rgba(50, 199, 135, .25)
        }

        .was-validated textarea.form-control:valid, textarea.form-control.is-valid {
            padding-right: 2.25rem;
            background-position: top calc(2.25rem / 4) right calc(2.25rem / 4)
        }

        .custom-select.is-valid, .was-validated .custom-select:valid {
            border-color: #32c787;
            padding-right: 3.4375rem;
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right .75rem center/8px 10px no-repeat, url(../img/forms/form-validation-valid.svg) center right 1.75rem/1.125rem 1.125rem no-repeat
        }

        .custom-select.is-valid:focus, .was-validated .custom-select:valid:focus {
            border-color: #32c787;
            box-shadow: 0 0 0 .2rem rgba(50, 199, 135, .25)
        }

        .custom-control-input.is-valid ~ .custom-control-label::before, .was-validated .custom-control-input:valid ~ .custom-control-label::before {
            border-color: #32c787
        }

        .custom-control-input.is-valid:checked ~ .custom-control-label::before, .was-validated .custom-control-input:valid:checked ~ .custom-control-label::before {
            border-color: #57d59f;
            background-color: #57d59f
        }

        .custom-control-input.is-valid:focus ~ .custom-control-label::before, .was-validated .custom-control-input:valid:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 .2rem rgba(50, 199, 135, .25)
        }

        .custom-control-input.is-valid:focus:not(:checked) ~ .custom-control-label::before, .custom-file-input.is-valid ~ .custom-file-label, .was-validated .custom-control-input:valid:focus:not(:checked) ~ .custom-control-label::before, .was-validated .custom-file-input:valid ~ .custom-file-label {
            border-color: #32c787
        }

        .custom-file-input.is-valid:focus ~ .custom-file-label, .was-validated .custom-file-input:valid:focus ~ .custom-file-label {
            border-color: #32c787;
            box-shadow: 0 0 0 .2rem rgba(50, 199, 135, .25)
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: .25rem;
            font-size: 80%;
            color: #ff6b68
        }

        .invalid-tooltip {
            display: none;
            font-size: .875rem;
            color: #FFF;
            background-color: #ff6b68
        }

        .collapsing, .dropdown, .dropleft, .dropright, .dropup {
            position: relative
        }

        .custom-control-input.is-invalid ~ .invalid-feedback, .custom-control-input.is-invalid ~ .invalid-tooltip, .custom-file-input.is-invalid ~ .invalid-feedback, .custom-file-input.is-invalid ~ .invalid-tooltip, .custom-select.is-invalid ~ .invalid-feedback, .custom-select.is-invalid ~ .invalid-tooltip, .form-check-input.is-invalid ~ .invalid-feedback, .form-check-input.is-invalid ~ .invalid-tooltip, .form-control-file.is-invalid ~ .invalid-feedback, .form-control-file.is-invalid ~ .invalid-tooltip, .form-control.is-invalid ~ .invalid-feedback, .form-control.is-invalid ~ .invalid-tooltip, .was-validated .custom-control-input:invalid ~ .invalid-feedback, .was-validated .custom-control-input:invalid ~ .invalid-tooltip, .was-validated .custom-file-input:invalid ~ .invalid-feedback, .was-validated .custom-file-input:invalid ~ .invalid-tooltip, .was-validated .custom-select:invalid ~ .invalid-feedback, .was-validated .custom-select:invalid ~ .invalid-tooltip, .was-validated .form-check-input:invalid ~ .invalid-feedback, .was-validated .form-check-input:invalid ~ .invalid-tooltip, .was-validated .form-control-file:invalid ~ .invalid-feedback, .was-validated .form-control-file:invalid ~ .invalid-tooltip, .was-validated .form-control:invalid ~ .invalid-feedback, .was-validated .form-control:invalid ~ .invalid-tooltip {
            display: block
        }

        .custom-control-input.is-invalid ~ .custom-control-label, .form-check-input.is-invalid ~ .form-check-label, .was-validated .custom-control-input:invalid ~ .custom-control-label, .was-validated .form-check-input:invalid ~ .form-check-label {
            color: #ff6b68
        }

        .form-control.is-invalid, .was-validated .form-control:invalid {
            border-color: #ff6b68;
            padding-right: 2.25rem;
            background-repeat: no-repeat;
            background-position: center right calc(2.25rem / 4);
            background-size: calc(2.25rem / 2) calc(2.25rem / 2);
            background-image: url(../img/forms/form-validation-invalid.svg)
        }

        .form-control.is-invalid:focus, .was-validated .form-control:invalid:focus {
            border-color: #ff6b68;
            box-shadow: 0 0 0 .2rem rgba(255, 107, 104, .25)
        }

        .was-validated textarea.form-control:invalid, textarea.form-control.is-invalid {
            padding-right: 2.25rem;
            background-position: top calc(2.25rem / 4) right calc(2.25rem / 4)
        }

        .custom-select.is-invalid, .was-validated .custom-select:invalid {
            border-color: #ff6b68;
            padding-right: 3.4375rem;
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right .75rem center/8px 10px no-repeat, url(../img/forms/form-validation-invalid.svg) center right 1.75rem/1.125rem 1.125rem no-repeat
        }

        .custom-select.is-invalid:focus, .was-validated .custom-select:invalid:focus {
            border-color: #ff6b68;
            box-shadow: 0 0 0 .2rem rgba(255, 107, 104, .25)
        }

        .custom-control-input.is-invalid ~ .custom-control-label::before, .was-validated .custom-control-input:invalid ~ .custom-control-label::before {
            border-color: #ff6b68
        }

        .custom-control-input.is-invalid:checked ~ .custom-control-label::before, .was-validated .custom-control-input:invalid:checked ~ .custom-control-label::before {
            border-color: #ff9d9b;
            background-color: #ff9d9b
        }

        .custom-control-input.is-invalid:focus ~ .custom-control-label::before, .was-validated .custom-control-input:invalid:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 .2rem rgba(255, 107, 104, .25)
        }

        .custom-control-input.is-invalid:focus:not(:checked) ~ .custom-control-label::before, .custom-file-input.is-invalid ~ .custom-file-label, .was-validated .custom-control-input:invalid:focus:not(:checked) ~ .custom-control-label::before, .was-validated .custom-file-input:invalid ~ .custom-file-label {
            border-color: #ff6b68
        }

        .custom-file-input.is-invalid:focus ~ .custom-file-label, .was-validated .custom-file-input:invalid:focus ~ .custom-file-label {
            border-color: #ff6b68;
            box-shadow: 0 0 0 .2rem rgba(255, 107, 104, .25)
        }

        .form-inline {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: horizontal;
            -ms-flex-flow: row wrap;
            flex-flow: row wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .form-inline .form-check {
            width: 100%
        }

        @media (min-width: 576px) {
            .form-inline label {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                margin-bottom: 0
            }

            .form-inline .form-group {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-flex: 0;
                -ms-flex: 0 0 auto;
                flex: 0 0 auto;
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-flow: row wrap;
                flex-flow: row wrap;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                margin-bottom: 0
            }

            .form-inline .form-control {
                display: inline-block;
                width: auto;
                vertical-align: middle
            }

            .form-inline .form-control-plaintext {
                display: inline-block
            }

            .form-inline .custom-select, .form-inline .input-group {
                width: auto
            }

            .form-inline .form-check {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center;
                width: auto;
                padding-left: 0
            }

            .form-inline .form-check-input {
                position: relative;
                margin-top: 0;
                margin-right: .25rem;
                margin-left: 0
            }

            .form-inline .custom-control {
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-pack: center;
                -ms-flex-pack: center;
                justify-content: center
            }

            .form-inline .custom-control-label {
                margin-bottom: 0
            }
        }

        .btn-block, input[type=button].btn-block, input[type=reset].btn-block, input[type=submit].btn-block {
            width: 100%
        }

        .btn {
            display: inline-block;
            color: #747a80;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 2px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 2px
        }

        .dropdown-toggle::after, .dropup .dropdown-toggle::after {
            vertical-align: .255em;
            content: ""
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .btn, .form-control {
                transition: none
            }
        }

        .btn:hover {
            color: #747a80
        }

        .btn.focus, .btn:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .25)
        }

        .btn-primary.focus, .btn-primary:focus, .btn-primary:not(:disabled):not(.disabled).active:focus, .btn-primary:not(:disabled):not(.disabled):active:focus, .show > .btn-primary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(66, 166, 245, .5)
        }

        .btn.disabled, .btn:disabled {
            opacity: .65
        }

        a.btn.disabled, fieldset:disabled a.btn {
            pointer-events: none
        }

        .btn-primary {
            color: #FFF;
            background-color: #2196F3;
            border-color: #2196F3
        }

        .btn-primary:hover {
            color: #FFF;
            background-color: #0c83e2;
            border-color: #0c7cd5
        }

        .btn-primary.disabled, .btn-primary:disabled {
            color: #FFF;
            background-color: #2196F3;
            border-color: #2196F3
        }

        .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .show > .btn-primary.dropdown-toggle {
            color: #FFF;
            background-color: #0c7cd5;
            border-color: #0b75c9
        }

        .btn-secondary.focus, .btn-secondary:focus, .btn-secondary:not(:disabled):not(.disabled).active:focus, .btn-secondary:not(:disabled):not(.disabled):active:focus, .show > .btn-secondary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(152, 159, 166, .5)
        }

        .btn-secondary {
            color: #FFF;
            background-color: #868e96;
            border-color: #868e96
        }

        .btn-secondary:hover {
            color: #FFF;
            background-color: #727b84;
            border-color: #6c757d
        }

        .btn-secondary.disabled, .btn-secondary:disabled {
            color: #FFF;
            background-color: #868e96;
            border-color: #868e96
        }

        .btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active, .show > .btn-secondary.dropdown-toggle {
            color: #FFF;
            background-color: #6c757d;
            border-color: #666e76
        }

        .btn-success.focus, .btn-success:focus, .btn-success:not(:disabled):not(.disabled).active:focus, .btn-success:not(:disabled):not(.disabled):active:focus, .show > .btn-success.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(81, 207, 153, .5)
        }

        .btn-success {
            color: #FFF;
            background-color: #32c787;
            border-color: #32c787
        }

        .btn-success:hover {
            color: #FFF;
            background-color: #2aa872;
            border-color: #289e6b
        }

        .btn-success.disabled, .btn-success:disabled {
            color: #FFF;
            background-color: #32c787;
            border-color: #32c787
        }

        .btn-success:not(:disabled):not(.disabled).active, .btn-success:not(:disabled):not(.disabled):active, .show > .btn-success.dropdown-toggle {
            color: #FFF;
            background-color: #289e6b;
            border-color: #259464
        }

        .btn-info.focus, .btn-info:focus, .btn-info:not(:disabled):not(.disabled).active:focus, .btn-info:not(:disabled):not(.disabled):active:focus, .show > .btn-info.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(41, 182, 246, .5)
        }

        .btn-info {
            color: #FFF;
            background-color: #03A9F4;
            border-color: #03A9F4
        }

        .btn-info:hover {
            color: #FFF;
            background-color: #038fce;
            border-color: #0286c2
        }

        .btn-info.disabled, .btn-info:disabled {
            color: #FFF;
            background-color: #03A9F4;
            border-color: #03A9F4
        }

        .btn-info:not(:disabled):not(.disabled).active, .btn-info:not(:disabled):not(.disabled):active, .show > .btn-info.dropdown-toggle {
            color: #FFF;
            background-color: #0286c2;
            border-color: #027db5
        }

        .btn-warning.focus, .btn-warning:focus, .btn-warning:not(:disabled):not(.disabled).active:focus, .btn-warning:not(:disabled):not(.disabled):active:focus, .show > .btn-warning.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(255, 207, 66, .5)
        }

        .btn-warning {
            color: #FFF;
            background-color: #ffc721;
            border-color: #ffc721
        }

        .btn-warning:hover {
            color: #FFF;
            background-color: #fabb00;
            border-color: #edb100
        }

        .btn-warning.disabled, .btn-warning:disabled {
            color: #FFF;
            background-color: #ffc721;
            border-color: #ffc721
        }

        .btn-warning:not(:disabled):not(.disabled).active, .btn-warning:not(:disabled):not(.disabled):active, .show > .btn-warning.dropdown-toggle {
            color: #FFF;
            background-color: #edb100;
            border-color: #e0a800
        }

        .btn-danger.focus, .btn-danger:focus, .btn-danger:not(:disabled):not(.disabled).active:focus, .btn-danger:not(:disabled):not(.disabled):active:focus, .show > .btn-danger.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(255, 129, 127, .5)
        }

        .btn-danger {
            color: #FFF;
            background-color: #ff6b68;
            border-color: #ff6b68
        }

        .btn-danger:hover {
            color: #FFF;
            background-color: #ff4642;
            border-color: #ff3935
        }

        .btn-danger.disabled, .btn-danger:disabled {
            color: #FFF;
            background-color: #ff6b68;
            border-color: #ff6b68
        }

        .btn-danger:not(:disabled):not(.disabled).active, .btn-danger:not(:disabled):not(.disabled):active, .show > .btn-danger.dropdown-toggle {
            color: #FFF;
            background-color: #ff3935;
            border-color: #ff2d28
        }

        .btn-light.focus, .btn-light:focus, .btn-light:not(:disabled):not(.disabled).active:focus, .btn-light:not(:disabled):not(.disabled):active:focus, .show > .btn-light.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(221, 223, 224, .5)
        }

        .btn-light {
            color: #525a62;
            background-color: #f6f6f6;
            border-color: #f6f6f6
        }

        .btn-light:hover {
            color: #525a62;
            background-color: #e3e3e3;
            border-color: #dddcdc
        }

        .btn-light.disabled, .btn-light:disabled {
            color: #525a62;
            background-color: #f6f6f6;
            border-color: #f6f6f6
        }

        .btn-light:not(:disabled):not(.disabled).active, .btn-light:not(:disabled):not(.disabled):active, .show > .btn-light.dropdown-toggle {
            color: #525a62;
            background-color: #dddcdc;
            border-color: #d6d6d6
        }

        .btn-dark.focus, .btn-dark:focus, .btn-dark:not(:disabled):not(.disabled).active:focus, .btn-dark:not(:disabled):not(.disabled):active:focus, .show > .btn-dark.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(100, 106, 112, .5)
        }

        .btn-dark {
            color: #FFF;
            background-color: #495057;
            border-color: #495057
        }

        .btn-dark:hover {
            color: #FFF;
            background-color: #383d42;
            border-color: #32373b
        }

        .btn-dark.disabled, .btn-dark:disabled {
            color: #FFF;
            background-color: #495057;
            border-color: #495057
        }

        .btn-dark:not(:disabled):not(.disabled).active, .btn-dark:not(:disabled):not(.disabled):active, .show > .btn-dark.dropdown-toggle {
            color: #FFF;
            background-color: #32373b;
            border-color: #2c3034
        }

        .btn-outline-primary.focus, .btn-outline-primary:focus, .btn-outline-primary:not(:disabled):not(.disabled).active:focus, .btn-outline-primary:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-primary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(33, 150, 243, .5)
        }

        .btn-outline-primary {
            color: #2196F3;
            border-color: #2196F3
        }

        .btn-outline-primary:hover {
            color: #FFF;
            background-color: #2196F3;
            border-color: #2196F3
        }

        .btn-outline-primary.disabled, .btn-outline-primary:disabled {
            color: #2196F3;
            background-color: transparent
        }

        .btn-outline-primary:not(:disabled):not(.disabled).active, .btn-outline-primary:not(:disabled):not(.disabled):active, .show > .btn-outline-primary.dropdown-toggle {
            color: #FFF;
            background-color: #2196F3;
            border-color: #2196F3
        }

        .btn-outline-secondary.focus, .btn-outline-secondary:focus, .btn-outline-secondary:not(:disabled):not(.disabled).active:focus, .btn-outline-secondary:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-secondary.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(134, 142, 150, .5)
        }

        .btn-outline-secondary {
            color: #868e96;
            border-color: #868e96
        }

        .btn-outline-secondary:hover {
            color: #FFF;
            background-color: #868e96;
            border-color: #868e96
        }

        .btn-outline-secondary.disabled, .btn-outline-secondary:disabled {
            color: #868e96;
            background-color: transparent
        }

        .btn-outline-secondary:not(:disabled):not(.disabled).active, .btn-outline-secondary:not(:disabled):not(.disabled):active, .show > .btn-outline-secondary.dropdown-toggle {
            color: #FFF;
            background-color: #868e96;
            border-color: #868e96
        }

        .btn-outline-success.focus, .btn-outline-success:focus, .btn-outline-success:not(:disabled):not(.disabled).active:focus, .btn-outline-success:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-success.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(50, 199, 135, .5)
        }

        .btn-outline-success {
            color: #32c787;
            border-color: #32c787
        }

        .btn-outline-success:hover {
            color: #FFF;
            background-color: #32c787;
            border-color: #32c787
        }

        .btn-outline-success.disabled, .btn-outline-success:disabled {
            color: #32c787;
            background-color: transparent
        }

        .btn-outline-success:not(:disabled):not(.disabled).active, .btn-outline-success:not(:disabled):not(.disabled):active, .show > .btn-outline-success.dropdown-toggle {
            color: #FFF;
            background-color: #32c787;
            border-color: #32c787
        }

        .btn-outline-info.focus, .btn-outline-info:focus, .btn-outline-info:not(:disabled):not(.disabled).active:focus, .btn-outline-info:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-info.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(3, 169, 244, .5)
        }

        .btn-outline-info {
            color: #03A9F4;
            border-color: #03A9F4
        }

        .btn-outline-info:hover {
            color: #FFF;
            background-color: #03A9F4;
            border-color: #03A9F4
        }

        .btn-outline-info.disabled, .btn-outline-info:disabled {
            color: #03A9F4;
            background-color: transparent
        }

        .btn-outline-info:not(:disabled):not(.disabled).active, .btn-outline-info:not(:disabled):not(.disabled):active, .show > .btn-outline-info.dropdown-toggle {
            color: #FFF;
            background-color: #03A9F4;
            border-color: #03A9F4
        }

        .btn-outline-warning.focus, .btn-outline-warning:focus, .btn-outline-warning:not(:disabled):not(.disabled).active:focus, .btn-outline-warning:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-warning.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(255, 199, 33, .5)
        }

        .btn-outline-warning {
            color: #ffc721;
            border-color: #ffc721
        }

        .btn-outline-warning:hover {
            color: #FFF;
            background-color: #ffc721;
            border-color: #ffc721
        }

        .btn-outline-warning.disabled, .btn-outline-warning:disabled {
            color: #ffc721;
            background-color: transparent
        }

        .btn-outline-warning:not(:disabled):not(.disabled).active, .btn-outline-warning:not(:disabled):not(.disabled):active, .show > .btn-outline-warning.dropdown-toggle {
            color: #FFF;
            background-color: #ffc721;
            border-color: #ffc721
        }

        .btn-outline-danger.focus, .btn-outline-danger:focus, .btn-outline-danger:not(:disabled):not(.disabled).active:focus, .btn-outline-danger:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-danger.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(255, 107, 104, .5)
        }

        .btn-outline-danger {
            color: #ff6b68;
            border-color: #ff6b68
        }

        .btn-outline-danger:hover {
            color: #FFF;
            background-color: #ff6b68;
            border-color: #ff6b68
        }

        .btn-outline-danger.disabled, .btn-outline-danger:disabled {
            color: #ff6b68;
            background-color: transparent
        }

        .btn-outline-danger:not(:disabled):not(.disabled).active, .btn-outline-danger:not(:disabled):not(.disabled):active, .show > .btn-outline-danger.dropdown-toggle {
            color: #FFF;
            background-color: #ff6b68;
            border-color: #ff6b68
        }

        .btn-outline-light.focus, .btn-outline-light:focus, .btn-outline-light:not(:disabled):not(.disabled).active:focus, .btn-outline-light:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-light.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(246, 246, 246, .5)
        }

        .btn-outline-light {
            color: #f6f6f6;
            border-color: #f6f6f6
        }

        .btn-outline-light:hover {
            color: #525a62;
            background-color: #f6f6f6;
            border-color: #f6f6f6
        }

        .btn-outline-light.disabled, .btn-outline-light:disabled {
            color: #f6f6f6;
            background-color: transparent
        }

        .btn-outline-light:not(:disabled):not(.disabled).active, .btn-outline-light:not(:disabled):not(.disabled):active, .show > .btn-outline-light.dropdown-toggle {
            color: #525a62;
            background-color: #f6f6f6;
            border-color: #f6f6f6
        }

        .btn-outline-dark.focus, .btn-outline-dark:focus, .btn-outline-dark:not(:disabled):not(.disabled).active:focus, .btn-outline-dark:not(:disabled):not(.disabled):active:focus, .show > .btn-outline-dark.dropdown-toggle:focus {
            box-shadow: 0 0 0 0 rgba(73, 80, 87, .5)
        }

        .btn-outline-dark {
            color: #495057;
            border-color: #495057
        }

        .btn-outline-dark:hover {
            color: #FFF;
            background-color: #495057;
            border-color: #495057
        }

        .btn-outline-dark.disabled, .btn-outline-dark:disabled {
            color: #495057;
            background-color: transparent
        }

        .btn-outline-dark:not(:disabled):not(.disabled).active, .btn-outline-dark:not(:disabled):not(.disabled):active, .show > .btn-outline-dark.dropdown-toggle {
            color: #FFF;
            background-color: #495057;
            border-color: #495057
        }

        .btn-link {
            color: #2196F3
        }

        .btn-link:hover {
            color: #0a6ebd
        }

        .btn-link.focus, .btn-link:focus {
            box-shadow: none
        }

        .btn-link.disabled, .btn-link:disabled {
            color: #868e96;
            pointer-events: none
        }

        .btn-group-lg > .btn, .btn-lg {
            padding: .5rem 1rem;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 2px
        }

        .btn-group-sm > .btn, .btn-sm {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 2px
        }

        .btn-block {
            display: block
        }

        .btn-block + .btn-block {
            margin-top: .5rem
        }

        .fade {
            transition: opacity .15s linear
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .fade {
                transition: none
            }
        }

        .fade:not(.show) {
            opacity: 0
        }

        .collapse:not(.show) {
            display: none
        }

        .collapsing {
            height: 0;
            transition: height .35s ease
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .collapsing {
                transition: none
            }
        }

        .dropdown-toggle::after {
            display: inline-block;
            margin-left: .255em;
            border-top: .3em solid;
            border-right: .3em solid transparent;
            border-bottom: 0;
            border-left: .3em solid transparent
        }

        .dropdown-toggle:empty::after {
            margin-left: 0
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 10rem;
            padding: .8rem 0;
            margin: 0;
            font-size: 1rem;
            color: #747a80;
            text-align: left;
            background-color: #FFF;
            background-clip: padding-box;
            border: 0 solid transparent;
            border-radius: 2px
        }

        .dropdown-menu-right {
            right: 0;
            left: auto
        }

        @media (min-width: 576px) {
            .dropdown-menu-sm-right {
                right: 0;
                left: auto
            }
        }

        @media (min-width: 768px) {
            .dropdown-menu-md-right {
                right: 0;
                left: auto
            }
        }

        @media (min-width: 992px) {
            .dropdown-menu-lg-right {
                right: 0;
                left: auto
            }
        }

        @media (min-width: 1200px) {
            .dropdown-menu-xl-right {
                right: 0;
                left: auto
            }

            .dropdown-menu-xl-left {
                right: auto;
                left: 0
            }
        }

        .dropdown-menu-left {
            right: auto;
            left: 0
        }

        @media (min-width: 576px) {
            .dropdown-menu-sm-left {
                right: auto;
                left: 0
            }
        }

        @media (min-width: 768px) {
            .dropdown-menu-md-left {
                right: auto;
                left: 0
            }
        }

        @media (min-width: 992px) {
            .dropdown-menu-lg-left {
                right: auto;
                left: 0
            }
        }

        .dropup .dropdown-menu {
            top: auto;
            bottom: 100%;
            margin-top: 0;
            margin-bottom: 0
        }

        .dropup .dropdown-toggle::after {
            display: inline-block;
            margin-left: .255em;
            border-top: 0;
            border-right: .3em solid transparent;
            border-bottom: .3em solid;
            border-left: .3em solid transparent
        }

        .dropleft .dropdown-toggle::before, .dropright .dropdown-toggle::after {
            content: "";
            border-top: .3em solid transparent;
            border-bottom: .3em solid transparent
        }

        .dropup .dropdown-toggle:empty::after {
            margin-left: 0
        }

        .dropright .dropdown-menu {
            top: 0;
            right: auto;
            left: 100%;
            margin-top: 0;
            margin-left: 0
        }

        .dropright .dropdown-toggle::after {
            display: inline-block;
            margin-left: .255em;
            border-right: 0;
            border-left: .3em solid;
            vertical-align: 0
        }

        .dropright .dropdown-toggle:empty::after {
            margin-left: 0
        }

        .dropleft .dropdown-menu {
            top: 0;
            right: 100%;
            left: auto;
            margin-top: 0;
            margin-right: 0
        }

        .dropleft .dropdown-toggle::after {
            margin-left: .255em;
            vertical-align: .255em;
            content: "";
            display: none
        }

        .dropleft .dropdown-toggle::before {
            display: inline-block;
            margin-right: .255em;
            border-right: .3em solid;
            vertical-align: 0
        }

        .dropleft .dropdown-toggle:empty::after {
            margin-left: 0
        }

        .dropdown-menu[x-placement^=top], .dropdown-menu[x-placement^=right], .dropdown-menu[x-placement^=bottom], .dropdown-menu[x-placement^=left] {
            right: auto;
            bottom: auto
        }

        .dropdown-divider {
            height: 0;
            margin: .5rem 0;
            overflow: hidden;
            border-top: 1px solid #f6f6f6
        }

        .btn-group-toggle > .btn, .btn-group-toggle > .btn-group > .btn, .custom-control-label, .custom-file, .dropdown-header, .input-group-text, .nav {
            margin-bottom: 0
        }

        .dropdown-item {
            display: block;
            width: 100%;
            padding: .5rem 1.5rem;
            clear: both;
            color: #6d6d6d;
            text-align: inherit;
            white-space: nowrap;
            background-color: transparent;
            border: 0
        }

        .dropdown-item:first-child {
            border-top-left-radius: calc(2px - 0);
            border-top-right-radius: calc(2px - 0)
        }

        .btn-group > .btn-group:not(:first-child) > .btn, .btn-group > .btn:not(:first-child), .input-group > .custom-file:not(:first-child) .custom-file-label, .input-group > .custom-select:not(:first-child), .input-group > .form-control:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0
        }

        .btn-group > .btn-group:not(:last-child) > .btn, .btn-group > .btn:not(:last-child):not(.dropdown-toggle), .input-group > .custom-file:not(:last-child) .custom-file-label, .input-group > .custom-file:not(:last-child) .custom-file-label::after, .input-group > .custom-select:not(:last-child), .input-group > .form-control:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0
        }

        .dropdown-item:last-child {
            border-bottom-right-radius: calc(2px - 0);
            border-bottom-left-radius: calc(2px - 0)
        }

        .dropdown-item:focus, .dropdown-item:hover {
            color: #16181b;
            background-color: #f9f9f9
        }

        .dropdown-item.active, .dropdown-item:active {
            color: #6d6d6d;
            text-decoration: none;
            background-color: #f6f6f6
        }

        .dropdown-item.disabled, .dropdown-item:disabled {
            color: #adb5bd;
            pointer-events: none;
            background-color: transparent
        }

        .dropdown-menu.show {
            display: block
        }

        .dropdown-header {
            display: block;
            padding: .8rem 1.5rem;
            color: #adb5bd;
            white-space: nowrap
        }

        .dropdown-item-text {
            display: block;
            padding: .5rem 1.5rem;
            color: #6d6d6d
        }

        .btn-group, .btn-group-vertical {
            position: relative;
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            vertical-align: middle
        }

        .btn-toolbar, .input-group {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .btn-group-vertical > .btn, .btn-group > .btn {
            position: relative;
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto
        }

        .btn-group-vertical > .btn.active, .btn-group-vertical > .btn:active, .btn-group-vertical > .btn:focus, .btn-group-vertical > .btn:hover, .btn-group > .btn.active, .btn-group > .btn:active, .btn-group > .btn:focus, .btn-group > .btn:hover {
            z-index: 1
        }

        .btn-toolbar {
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start
        }

        .btn-toolbar .input-group {
            width: auto
        }

        .btn-group > .btn-group:not(:first-child), .btn-group > .btn:not(:first-child) {
            margin-left: -2px
        }

        .dropdown-toggle-split {
            padding-right: .5625rem;
            padding-left: .5625rem
        }

        .dropdown-toggle-split::after, .dropright .dropdown-toggle-split::after, .dropup .dropdown-toggle-split::after {
            margin-left: 0
        }

        .input-group-append, .input-group-append .btn + .btn, .input-group-append .btn + .input-group-text, .input-group-append .input-group-text + .btn, .input-group-append .input-group-text + .input-group-text, .input-group-prepend .btn + .btn, .input-group-prepend .btn + .input-group-text, .input-group-prepend .input-group-text + .btn, .input-group-prepend .input-group-text + .input-group-text, .input-group > .custom-file + .custom-file, .input-group > .custom-file + .custom-select, .input-group > .custom-file + .form-control, .input-group > .custom-select + .custom-file, .input-group > .custom-select + .custom-select, .input-group > .custom-select + .form-control, .input-group > .form-control + .custom-file, .input-group > .form-control + .custom-select, .input-group > .form-control + .form-control, .input-group > .form-control-plaintext + .custom-file, .input-group > .form-control-plaintext + .custom-select, .input-group > .form-control-plaintext + .form-control {
            margin-left: -1px
        }

        .dropleft .dropdown-toggle-split::before {
            margin-right: 0
        }

        .btn-group-sm > .btn + .dropdown-toggle-split, .btn-sm + .dropdown-toggle-split {
            padding-right: .375rem;
            padding-left: .375rem
        }

        .btn-group-lg > .btn + .dropdown-toggle-split, .btn-lg + .dropdown-toggle-split {
            padding-right: .75rem;
            padding-left: .75rem
        }

        .btn-group-vertical {
            -webkit-box-orient: vertical;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .btn-group-vertical > .btn, .btn-group-vertical > .btn-group {
            width: 100%
        }

        .btn-group-vertical > .btn-group:not(:first-child), .btn-group-vertical > .btn:not(:first-child) {
            margin-top: -2px
        }

        .btn-group-vertical > .btn-group:not(:last-child) > .btn, .btn-group-vertical > .btn:not(:last-child):not(.dropdown-toggle) {
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0
        }

        .btn-group-vertical > .btn-group:not(:first-child) > .btn, .btn-group-vertical > .btn:not(:first-child) {
            border-top-left-radius: 0;
            border-top-right-radius: 0
        }

        .btn-group-toggle > .btn input[type=radio], .btn-group-toggle > .btn input[type=checkbox], .btn-group-toggle > .btn-group > .btn input[type=radio], .btn-group-toggle > .btn-group > .btn input[type=checkbox] {
            position: absolute;
            clip: rect(0, 0, 0, 0);
            pointer-events: none
        }

        .input-group {
            position: relative;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: stretch;
            -ms-flex-align: stretch;
            align-items: stretch;
            width: 100%
        }

        .input-group > .custom-file, .input-group > .custom-select, .input-group > .form-control, .input-group > .form-control-plaintext {
            position: relative;
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            width: 1%;
            margin-bottom: 0
        }

        .input-group > .custom-file .custom-file-input:focus ~ .custom-file-label, .input-group > .custom-select:focus, .input-group > .form-control:focus {
            z-index: 3
        }

        .input-group > .custom-file .custom-file-input:focus {
            z-index: 4
        }

        .input-group > .custom-file {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .input-group-append, .input-group-prepend {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .input-group-text, .nav, .navbar {
            display: -webkit-box
        }

        .input-group-append .btn, .input-group-prepend .btn {
            position: relative;
            z-index: 2
        }

        .input-group-append .btn:focus, .input-group-prepend .btn:focus {
            z-index: 3
        }

        .input-group-prepend {
            margin-right: -1px
        }

        .input-group-text {
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: .375rem 0;
            font-size: 1rem;
            color: #495057;
            text-align: center;
            white-space: nowrap;
            background-color: #FFF;
            border: 1px solid #eceff1;
            border-radius: 0
        }

        .input-group-text input[type=radio], .input-group-text input[type=checkbox] {
            margin-top: 0
        }

        .input-group-lg > .custom-select, .input-group-lg > .form-control:not(textarea) {
            height: calc(2.875rem + 2px)
        }

        .input-group-lg > .custom-select, .input-group-lg > .form-control, .input-group-lg > .input-group-append > .btn, .input-group-lg > .input-group-append > .input-group-text, .input-group-lg > .input-group-prepend > .btn, .input-group-lg > .input-group-prepend > .input-group-text {
            padding: .5rem 0;
            font-size: 1.25rem;
            line-height: 1.5;
            border-radius: 0
        }

        .input-group-sm > .custom-select, .input-group-sm > .form-control:not(textarea) {
            height: calc(1.8125rem + 2px)
        }

        .input-group-sm > .custom-select, .input-group-sm > .form-control, .input-group-sm > .input-group-append > .btn, .input-group-sm > .input-group-append > .input-group-text, .input-group-sm > .input-group-prepend > .btn, .input-group-sm > .input-group-prepend > .input-group-text {
            padding: .25rem 0;
            font-size: .875rem;
            line-height: 1.5;
            border-radius: 0
        }

        .input-group-lg > .custom-select, .input-group-sm > .custom-select {
            padding-right: 1.75rem
        }

        .input-group > .input-group-append:last-child > .btn:not(:last-child):not(.dropdown-toggle), .input-group > .input-group-append:last-child > .input-group-text:not(:last-child), .input-group > .input-group-append:not(:last-child) > .btn, .input-group > .input-group-append:not(:last-child) > .input-group-text, .input-group > .input-group-prepend > .btn, .input-group > .input-group-prepend > .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0
        }

        .input-group > .input-group-append > .btn, .input-group > .input-group-append > .input-group-text, .input-group > .input-group-prepend:first-child > .btn:not(:first-child), .input-group > .input-group-prepend:first-child > .input-group-text:not(:first-child), .input-group > .input-group-prepend:not(:first-child) > .btn, .input-group > .input-group-prepend:not(:first-child) > .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0
        }

        .custom-control {
            position: relative;
            display: block;
            min-height: 1.5rem;
            padding-left: 1.5rem
        }

        .custom-control-inline {
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            margin-right: 1rem
        }

        .custom-control-input {
            position: absolute;
            z-index: -1;
            opacity: 0
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            color: #FFF;
            border-color: #2196F3;
            background-color: #2196F3
        }

        .custom-control-input:focus ~ .custom-control-label::before {
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .25)
        }

        .custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
            border-color: #eceff1
        }

        .custom-control-input:not(:disabled):active ~ .custom-control-label::before {
            color: #FFF;
            background-color: #cae6fc;
            border-color: #cae6fc
        }

        .custom-control-input:disabled ~ .custom-control-label {
            color: #868e96
        }

        .custom-control-input:disabled ~ .custom-control-label::before {
            background-color: transparent
        }

        .custom-control-label {
            position: relative;
            vertical-align: top
        }

        .custom-control-label::after, .custom-control-label::before {
            position: absolute;
            top: .25rem;
            left: -1.5rem;
            display: block;
            width: 1rem;
            height: 1rem;
            content: ""
        }

        .custom-control-label::before {
            pointer-events: none;
            background-color: transparent;
            border: 1px solid #adb5bd
        }

        .custom-control-label::after {
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 50% 50%
        }

        .custom-checkbox .custom-control-label::before {
            border-radius: 2px
        }

        .custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23FFFFFF' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26 2.974 7.25 8 2.193z'/%3e%3c/svg%3e")
        }

        .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
            border-color: #2196F3;
            background-color: #2196F3
        }

        .custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before, .custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before, .custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
            background-color: rgba(33, 150, 243, .5)
        }

        .custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 4'%3e%3cpath stroke='%23FFFFFF' d='M0 2h4'/%3e%3c/svg%3e")
        }

        .custom-radio .custom-control-label::before {
            border-radius: 50%
        }

        .custom-radio .custom-control-input:checked ~ .custom-control-label::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23FFFFFF'/%3e%3c/svg%3e")
        }

        .custom-switch {
            padding-left: 2.25rem
        }

        .custom-switch .custom-control-label::before {
            left: -2.25rem;
            width: 1.75rem;
            pointer-events: all;
            border-radius: .5rem
        }

        .custom-switch .custom-control-label::after {
            top: calc(.25rem + 2px);
            left: calc(-2.25rem + 2px);
            width: calc(1rem - 4px);
            height: calc(1rem - 4px);
            background-color: #adb5bd;
            border-radius: .5rem;
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out;
            transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            transition: transform .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out, -webkit-transform .15s ease-in-out
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .custom-switch .custom-control-label::after {
                transition: none
            }
        }

        .custom-switch .custom-control-input:checked ~ .custom-control-label::after {
            background-color: transparent;
            -webkit-transform: translateX(.75rem);
            transform: translateX(.75rem)
        }

        .custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
            background-color: rgba(33, 150, 243, .5)
        }

        .custom-select {
            display: inline-block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem 1.75rem .375rem .75rem;
            line-height: 1.5;
            color: #495057;
            vertical-align: middle;
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") right .75rem center/8px 10px no-repeat;
            border: 1px solid #eceff1;
            border-radius: 2px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none
        }

        .custom-select:focus {
            border-color: #eceff1;
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(236, 239, 241, .5)
        }

        .custom-select:focus::-ms-value {
            color: #495057;
            background-color: transparent
        }

        .custom-select[multiple], .custom-select[size]:not([size="1"]) {
            height: auto;
            padding-right: .75rem;
            background-image: none
        }

        .custom-select:disabled {
            color: #868e96;
            background-color: #e9ecef
        }

        .custom-file-input:disabled ~ .custom-file-label, .custom-file-label {
            background-color: transparent
        }

        .custom-select::-ms-expand {
            opacity: 0
        }

        .custom-select-sm {
            height: calc(1.8125rem + 2px);
            padding-top: .25rem;
            padding-bottom: .25rem;
            padding-left: 0;
            font-size: .875rem
        }

        .custom-select-lg {
            height: calc(2.875rem + 2px);
            padding-top: .5rem;
            padding-bottom: .5rem;
            padding-left: 0;
            font-size: 1.25rem
        }

        .custom-file, .custom-file-input, .custom-file-label {
            height: calc(2.25rem + 2px)
        }

        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%
        }

        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            margin: 0;
            opacity: 0
        }

        .custom-file-label, .custom-file-label::after {
            position: absolute;
            padding: .375rem 0;
            line-height: 1.5;
            color: #495057;
            top: 0;
            right: 0
        }

        .custom-file-input:focus ~ .custom-file-label {
            border-color: #eceff1;
            box-shadow: none
        }

        .custom-file-input:lang(en) ~ .custom-file-label::after {
            content: "Browse"
        }

        .custom-file-input ~ .custom-file-label[data-browse]::after {
            content: attr(data-browse)
        }

        .custom-file-label {
            left: 0;
            z-index: 1;
            font-weight: 400;
            border: 1px solid #eceff1;
            border-radius: 0
        }

        .custom-file-label::after {
            bottom: 0;
            z-index: 3;
            display: block;
            height: 2.25rem;
            content: "Browse";
            background-color: #FFF;
            border-left: inherit;
            border-radius: 0
        }

        .nav, .navbar {
            display: -ms-flexbox;
            -ms-flex-wrap: wrap
        }

        .custom-range {
            width: 100%;
            height: calc(1rem + .4rem);
            padding: 0;
            background-color: transparent;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none
        }

        .custom-range:focus {
            outline: 0
        }

        .custom-range:focus::-webkit-slider-thumb {
            box-shadow: 0 0 0 1px #f3f3f3, none
        }

        .custom-range:focus::-moz-range-thumb {
            box-shadow: 0 0 0 1px #f3f3f3, none
        }

        .custom-range:focus::-ms-thumb {
            box-shadow: 0 0 0 1px #f3f3f3, none
        }

        .custom-range::-moz-focus-outer {
            border: 0
        }

        .custom-range::-webkit-slider-thumb {
            width: 1rem;
            height: 1rem;
            margin-top: -.25rem;
            background-color: #2196F3;
            border: 0;
            border-radius: 1rem;
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            -webkit-appearance: none;
            appearance: none
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .custom-range::-webkit-slider-thumb {
                transition: none
            }
        }

        .custom-range::-webkit-slider-thumb:active {
            background-color: #cae6fc
        }

        .custom-range::-webkit-slider-runnable-track {
            width: 100%;
            height: .5rem;
            color: transparent;
            cursor: pointer;
            background-color: #dee2e6;
            border-color: transparent;
            border-radius: 1rem
        }

        .custom-range::-moz-range-thumb {
            width: 1rem;
            height: 1rem;
            background-color: #2196F3;
            border: 0;
            border-radius: 1rem;
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            -moz-appearance: none;
            appearance: none
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .custom-range::-moz-range-thumb {
                transition: none
            }
        }

        .custom-range::-moz-range-thumb:active {
            background-color: #cae6fc
        }

        .custom-range::-moz-range-track {
            width: 100%;
            height: .5rem;
            color: transparent;
            cursor: pointer;
            background-color: #dee2e6;
            border-color: transparent;
            border-radius: 1rem
        }

        .custom-range::-ms-thumb {
            width: 1rem;
            height: 1rem;
            margin-top: 0;
            margin-right: .2rem;
            margin-left: .2rem;
            background-color: #2196F3;
            border: 0;
            border-radius: 1rem;
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            appearance: none
        }

        .form-control, button.close {
            -webkit-appearance: none;
            -moz-appearance: none
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .custom-range::-ms-thumb {
                transition: none
            }
        }

        .custom-range::-ms-thumb:active {
            background-color: #cae6fc
        }

        .custom-range::-ms-track {
            width: 100%;
            height: .5rem;
            color: transparent;
            cursor: pointer;
            background-color: transparent;
            border-color: transparent;
            border-width: .5rem
        }

        .custom-range::-ms-fill-lower {
            background-color: #dee2e6;
            border-radius: 1rem
        }

        .custom-range::-ms-fill-upper {
            margin-right: 15px;
            background-color: #dee2e6;
            border-radius: 1rem
        }

        .nav-tabs .dropdown-menu, .nav-tabs .nav-link {
            border-top-left-radius: 0;
            border-top-right-radius: 0
        }

        .custom-range:disabled::-webkit-slider-thumb {
            background-color: #adb5bd
        }

        .custom-range:disabled::-webkit-slider-runnable-track {
            cursor: default
        }

        .custom-range:disabled::-moz-range-thumb {
            background-color: #adb5bd
        }

        .custom-range:disabled::-moz-range-track {
            cursor: default
        }

        .custom-range:disabled::-ms-thumb {
            background-color: #adb5bd
        }

        .custom-control-label::before, .custom-file-label, .custom-select {
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .custom-control-label::before, .custom-file-label, .custom-select {
                transition: none
            }
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            padding-left: 0
        }

        .nav-link {
            display: block;
            padding: 1rem 1.2rem
        }

        .nav-link.disabled {
            color: #868e96;
            pointer-events: none;
            cursor: default
        }

        .navbar-toggler:not(:disabled):not(.disabled), .page-link:not(:disabled):not(.disabled) {
            cursor: pointer
        }

        .nav-tabs {
            border-bottom: 2px solid #f6f6f6
        }

        .nav-tabs .nav-item {
            margin-bottom: -2px
        }

        .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #f6f6f6
        }

        .nav-tabs .nav-link.disabled {
            color: #868e96;
            background-color: transparent;
            border-color: transparent
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: inherit;
            background-color: transparent;
            border-color: #dee2e6 #dee2e6 transparent
        }

        .nav-tabs .dropdown-menu {
            margin-top: -2px
        }

        .nav-pills .nav-link {
            border-radius: 2px
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: #FFF;
            background-color: #2196F3
        }

        .nav-fill .nav-item {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            text-align: center
        }

        .nav-justified .nav-item {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            text-align: center
        }

        .tab-content > .tab-pane {
            display: none
        }

        .tab-content > .active {
            display: block
        }

        .navbar {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: .5rem 1rem
        }

        .navbar > .container, .navbar > .container-fluid {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between
        }

        .navbar-brand {
            display: inline-block;
            padding-top: .8125rem;
            padding-bottom: .8125rem;
            margin-right: 1rem;
            font-size: 1.25rem;
            line-height: inherit;
            white-space: nowrap
        }

        .card, .navbar-nav {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .navbar-nav {
            display: flex;
            -webkit-box-orient: vertical;
            -ms-flex-direction: column;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0
        }

        .navbar-nav .nav-link {
            padding-right: 0;
            padding-left: 0
        }

        .navbar-nav .dropdown-menu {
            position: static;
            float: none
        }

        .navbar-text {
            display: inline-block;
            padding-top: 1rem;
            padding-bottom: 1rem
        }

        .navbar-collapse {
            -ms-flex-preferred-size: 100%;
            flex-basis: 100%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .navbar-toggler {
            padding: .25rem .75rem;
            font-size: 1.25rem;
            line-height: 1;
            background-color: transparent;
            border: 1px solid transparent;
            border-radius: 2px
        }

        .navbar-toggler-icon {
            display: inline-block;
            width: 1.5em;
            height: 1.5em;
            vertical-align: middle;
            content: "";
            background: center center no-repeat;
            background-size: 100% 100%
        }

        @media (max-width: 575.98px) {
            .navbar-expand-sm > .container, .navbar-expand-sm > .container-fluid {
                padding-right: 0;
                padding-left: 0
            }
        }

        @media (min-width: 576px) {
            .navbar-expand-sm, .navbar-expand-sm .navbar-nav {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal
            }

            .navbar-expand-sm {
                -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start
            }

            .navbar-expand-sm .navbar-nav {
                -ms-flex-direction: row;
                flex-direction: row
            }

            .navbar-expand-sm .navbar-nav .dropdown-menu {
                position: absolute
            }

            .navbar-expand-sm .navbar-nav .nav-link {
                padding-right: .5rem;
                padding-left: .5rem
            }

            .navbar-expand-sm > .container, .navbar-expand-sm > .container-fluid {
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap
            }

            .navbar-expand-sm .navbar-collapse {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -ms-flex-preferred-size: auto;
                flex-basis: auto
            }

            .navbar-expand-sm .navbar-toggler {
                display: none
            }
        }

        @media (max-width: 767.98px) {
            .navbar-expand-md > .container, .navbar-expand-md > .container-fluid {
                padding-right: 0;
                padding-left: 0
            }
        }

        @media (min-width: 768px) {
            .navbar-expand-md, .navbar-expand-md .navbar-nav {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal
            }

            .navbar-expand-md {
                -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start
            }

            .navbar-expand-md .navbar-nav {
                -ms-flex-direction: row;
                flex-direction: row
            }

            .navbar-expand-md .navbar-nav .dropdown-menu {
                position: absolute
            }

            .navbar-expand-md .navbar-nav .nav-link {
                padding-right: .5rem;
                padding-left: .5rem
            }

            .navbar-expand-md > .container, .navbar-expand-md > .container-fluid {
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap
            }

            .navbar-expand-md .navbar-collapse {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -ms-flex-preferred-size: auto;
                flex-basis: auto
            }

            .navbar-expand-md .navbar-toggler {
                display: none
            }
        }

        @media (max-width: 991.98px) {
            .navbar-expand-lg > .container, .navbar-expand-lg > .container-fluid {
                padding-right: 0;
                padding-left: 0
            }
        }

        @media (min-width: 992px) {
            .navbar-expand-lg, .navbar-expand-lg .navbar-nav {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal
            }

            .navbar-expand-lg {
                -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start
            }

            .navbar-expand-lg .navbar-nav {
                -ms-flex-direction: row;
                flex-direction: row
            }

            .navbar-expand-lg .navbar-nav .dropdown-menu {
                position: absolute
            }

            .navbar-expand-lg .navbar-nav .nav-link {
                padding-right: .5rem;
                padding-left: .5rem
            }

            .navbar-expand-lg > .container, .navbar-expand-lg > .container-fluid {
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap
            }

            .navbar-expand-lg .navbar-collapse {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -ms-flex-preferred-size: auto;
                flex-basis: auto
            }

            .navbar-expand-lg .navbar-toggler {
                display: none
            }
        }

        @media (max-width: 1199.98px) {
            .navbar-expand-xl > .container, .navbar-expand-xl > .container-fluid {
                padding-right: 0;
                padding-left: 0
            }
        }

        @media (min-width: 1200px) {
            .navbar-expand-xl, .navbar-expand-xl .navbar-nav {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal
            }

            .navbar-expand-xl {
                -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
                -webkit-box-pack: start;
                -ms-flex-pack: start;
                justify-content: flex-start
            }

            .navbar-expand-xl .navbar-nav {
                -ms-flex-direction: row;
                flex-direction: row
            }

            .navbar-expand-xl .navbar-nav .dropdown-menu {
                position: absolute
            }

            .navbar-expand-xl .navbar-nav .nav-link {
                padding-right: .5rem;
                padding-left: .5rem
            }

            .navbar-expand-xl > .container, .navbar-expand-xl > .container-fluid {
                -ms-flex-wrap: nowrap;
                flex-wrap: nowrap
            }

            .navbar-expand-xl .navbar-collapse {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -ms-flex-preferred-size: auto;
                flex-basis: auto
            }

            .navbar-expand-xl .navbar-toggler {
                display: none
            }
        }

        .navbar-expand {
            -webkit-box-orient: horizontal;
            -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
            -webkit-box-pack: start;
            -ms-flex-pack: start;
            justify-content: flex-start
        }

        .navbar-expand > .container, .navbar-expand > .container-fluid {
            padding-right: 0;
            padding-left: 0
        }

        .navbar-expand .navbar-nav {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row
        }

        .card, .card-deck {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal
        }

        .navbar-expand .navbar-nav .dropdown-menu {
            position: absolute
        }

        .navbar-expand .navbar-nav .nav-link {
            padding-right: .5rem;
            padding-left: .5rem
        }

        .navbar-expand > .container, .navbar-expand > .container-fluid {
            -ms-flex-wrap: nowrap;
            flex-wrap: nowrap
        }

        .navbar-expand .navbar-collapse {
            display: -webkit-box !important;
            display: -ms-flexbox !important;
            display: flex !important;
            -ms-flex-preferred-size: auto;
            flex-basis: auto
        }

        .navbar-expand .navbar-toggler {
            display: none
        }

        .navbar-light .navbar-brand, .navbar-light .navbar-brand:focus, .navbar-light .navbar-brand:hover {
            color: rgba(0, 0, 0, .9)
        }

        .navbar-light .navbar-nav .nav-link {
            color: rgba(0, 0, 0, .5)
        }

        .navbar-light .navbar-nav .nav-link:focus, .navbar-light .navbar-nav .nav-link:hover {
            color: rgba(0, 0, 0, .7)
        }

        .navbar-light .navbar-nav .nav-link.disabled {
            color: rgba(0, 0, 0, .3)
        }

        .navbar-light .navbar-nav .active > .nav-link, .navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .nav-link.show, .navbar-light .navbar-nav .show > .nav-link {
            color: rgba(0, 0, 0, .9)
        }

        .navbar-light .navbar-toggler {
            color: rgba(0, 0, 0, .5);
            border-color: rgba(0, 0, 0, .1)
        }

        .navbar-light .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(0, 0, 0, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e")
        }

        .navbar-light .navbar-text {
            color: rgba(0, 0, 0, .5)
        }

        .navbar-light .navbar-text a, .navbar-light .navbar-text a:focus, .navbar-light .navbar-text a:hover {
            color: rgba(0, 0, 0, .9)
        }

        .navbar-dark .navbar-brand, .navbar-dark .navbar-brand:focus, .navbar-dark .navbar-brand:hover {
            color: #FFF
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, .5)
        }

        .navbar-dark .navbar-nav .nav-link:focus, .navbar-dark .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, .75)
        }

        .navbar-dark .navbar-nav .nav-link.disabled {
            color: rgba(255, 255, 255, .25)
        }

        .navbar-dark .navbar-nav .active > .nav-link, .navbar-dark .navbar-nav .nav-link.active, .navbar-dark .navbar-nav .nav-link.show, .navbar-dark .navbar-nav .show > .nav-link {
            color: #FFF
        }

        .navbar-dark .navbar-toggler {
            color: rgba(255, 255, 255, .5);
            border-color: rgba(255, 255, 255, .1)
        }

        .navbar-dark .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(255, 255, 255, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e")
        }

        .navbar-dark .navbar-text {
            color: rgba(255, 255, 255, .5)
        }

        .navbar-dark .navbar-text a, .navbar-dark .navbar-text a:focus, .navbar-dark .navbar-text a:hover {
            color: #FFF
        }

        .card {
            position: relative;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #FFF;
            background-clip: border-box;
            border: 1px solid transparent;
            border-radius: 2px
        }

        .card > hr {
            margin-right: 0;
            margin-left: 0
        }

        .card > .list-group:first-child .list-group-item:first-child {
            border-top-left-radius: 2px;
            border-top-right-radius: 2px
        }

        .card > .list-group:last-child .list-group-item:last-child {
            border-bottom-right-radius: 2px;
            border-bottom-left-radius: 2px
        }

        .card-body {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 2.2rem
        }

        .card-title {
            margin-bottom: 2.1rem
        }

        .card-subtitle, .card-text:last-child {
            margin-bottom: 0
        }

        .card-link + .card-link {
            margin-left: 2.2rem
        }

        .card-header-pills, .card-header-tabs {
            margin-right: -1.1rem;
            margin-left: -1.1rem
        }

        .card-header {
            padding: 2.1rem 2.2rem;
            color: inherit;
            border-bottom: 1px solid transparent
        }

        .card-header:first-child {
            border-radius: 2px 2px 0 0
        }

        .card-header + .list-group .list-group-item:first-child {
            border-top: 0
        }

        .card-footer {
            padding: 2.1rem 2.2rem;
            border-top: 1px solid transparent
        }

        .card-footer:last-child {
            border-radius: 0 0 2px 2px
        }

        .card-header-tabs {
            margin-bottom: -2.1rem;
            border-bottom: 0
        }

        .card-img-overlay {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 0
        }

        .alert, .btn .badge, .page-link {
            position: relative
        }

        .card-img {
            width: 100%;
            border-radius: 2px
        }

        .card-img-top {
            width: 100%;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px
        }

        .card-img-bottom {
            width: 100%;
            border-bottom-right-radius: 2px;
            border-bottom-left-radius: 2px
        }

        .card-deck {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column
        }

        .card-deck .card {
            margin-bottom: 15px
        }

        @media (min-width: 576px) {
            .card-deck, .card-deck .card {
                -webkit-box-direction: normal
            }

            .card-deck {
                -webkit-box-orient: horizontal;
                -ms-flex-flow: row wrap;
                flex-flow: row wrap;
                margin-right: -15px;
                margin-left: -15px
            }

            .card-deck .card {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-flex: 1;
                -ms-flex: 1 0 0%;
                flex: 1 0 0%;
                -webkit-box-orient: vertical;
                -ms-flex-direction: column;
                flex-direction: column;
                margin-right: 15px;
                margin-bottom: 0;
                margin-left: 15px
            }
        }

        .page-item:first-child .page-link, .page-link {
            margin-left: 0
        }

        .card-group {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column
        }

        .card-group > .card {
            margin-bottom: 15px
        }

        @media (min-width: 576px) {
            .card-group {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-flow: row wrap;
                flex-flow: row wrap
            }

            .card-group > .card {
                -webkit-box-flex: 1;
                -ms-flex: 1 0 0%;
                flex: 1 0 0%;
                margin-bottom: 0
            }

            .card-group > .card + .card {
                margin-left: 0;
                border-left: 0
            }

            .card-group > .card:first-child {
                border-top-right-radius: 0;
                border-bottom-right-radius: 0
            }

            .card-group > .card:first-child .card-header, .card-group > .card:first-child .card-img-top {
                border-top-right-radius: 0
            }

            .card-group > .card:first-child .card-footer, .card-group > .card:first-child .card-img-bottom {
                border-bottom-right-radius: 0
            }

            .card-group > .card:last-child {
                border-top-left-radius: 0;
                border-bottom-left-radius: 0
            }

            .card-group > .card:last-child .card-header, .card-group > .card:last-child .card-img-top {
                border-top-left-radius: 0
            }

            .card-group > .card:last-child .card-footer, .card-group > .card:last-child .card-img-bottom {
                border-bottom-left-radius: 0
            }

            .card-group > .card:only-child {
                border-radius: 2px
            }

            .card-group > .card:only-child .card-header, .card-group > .card:only-child .card-img-top {
                border-top-left-radius: 2px;
                border-top-right-radius: 2px
            }

            .card-group > .card:only-child .card-footer, .card-group > .card:only-child .card-img-bottom {
                border-bottom-right-radius: 2px;
                border-bottom-left-radius: 2px
            }

            .card-group > .card:not(:first-child):not(:last-child):not(:only-child), .card-group > .card:not(:first-child):not(:last-child):not(:only-child) .card-footer, .card-group > .card:not(:first-child):not(:last-child):not(:only-child) .card-header, .card-group > .card:not(:first-child):not(:last-child):not(:only-child) .card-img-bottom, .card-group > .card:not(:first-child):not(:last-child):not(:only-child) .card-img-top {
                border-radius: 0
            }

            .card-columns {
                -webkit-column-count: 3;
                column-count: 3;
                -webkit-column-gap: 1.25rem;
                column-gap: 1.25rem;
                orphans: 1;
                widows: 1
            }

            .card-columns .card {
                display: inline-block;
                width: 100%
            }
        }

        .list-group, .modal-content, .progress-bar {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal
        }

        .card-columns .card {
            margin-bottom: 2.3rem
        }

        .accordion .card:not(:first-of-type) .card-header:first-child {
            border-radius: 0
        }

        .accordion .card:not(:first-of-type):not(:last-of-type) {
            border-bottom: 0;
            border-radius: 0
        }

        .accordion .card:first-of-type {
            border-bottom: 0;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0
        }

        .accordion .card:last-of-type {
            border-top-left-radius: 0;
            border-top-right-radius: 0
        }

        .accordion .card .card-header {
            margin-bottom: -1px
        }

        .breadcrumb {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            padding: .75rem .25rem;
            margin-bottom: 1rem;
            background-color: transparent
        }

        .breadcrumb-item + .breadcrumb-item {
            padding-left: .5rem
        }

        .breadcrumb-item + .breadcrumb-item::before {
            display: inline-block;
            padding-right: .5rem;
            color: #868e96;
            content: ""
        }

        .pagination, .progress {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .carousel-inner::after, .clearfix::after, .embed-responsive::before, .modal-dialog-centered::before, .popover .arrow::after, .popover .arrow::before, .tooltip .arrow::before {
            content: ""
        }

        .breadcrumb-item.active {
            color: #868e96
        }

        .pagination {
            display: flex;
            padding-left: 0;
            border-radius: 2px
        }

        .page-link {
            display: block;
            padding: 0;
            color: #8e9499;
            background-color: #f3f3f3;
            border: 0 solid #dee2e6
        }

        .page-item:first-child .page-link, .pagination-lg .page-item:first-child .page-link, .pagination-sm .page-item:first-child .page-link {
            border-top-left-radius: 2px;
            border-bottom-left-radius: 2px
        }

        .page-item:last-child .page-link, .pagination-lg .page-item:last-child .page-link, .pagination-sm .page-item:last-child .page-link {
            border-top-right-radius: 2px;
            border-bottom-right-radius: 2px
        }

        .page-link:hover {
            z-index: 2;
            color: #81878d;
            background-color: #e6e6e6;
            border-color: #dee2e6
        }

        .page-link:focus {
            z-index: 2;
            outline: 0
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #FFF;
            background-color: #03A9F4;
            border-color: #03A9F4
        }

        .page-item.disabled .page-link {
            color: #8e9499;
            pointer-events: none;
            cursor: auto;
            background-color: #f3f3f3;
            border-color: #dee2e6
        }

        .pagination-lg .page-link {
            padding: .75rem 1.5rem;
            font-size: 1.25rem;
            line-height: 1.5
        }

        .pagination-sm .page-link {
            padding: .25rem .5rem;
            font-size: .875rem;
            line-height: 1.5
        }

        .badge, .close {
            line-height: 1
        }

        .badge {
            display: inline-block;
            padding: .5rem 1rem;
            font-size: 90%;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 2px
        }

        .alert-link, .close, .popover {
            font-weight: 400
        }

        .badge:empty {
            display: none
        }

        .btn .badge {
            top: -1px
        }

        .badge-pill {
            padding-right: 1rem;
            padding-left: 1rem;
            border-radius: 10rem
        }

        .badge-primary {
            color: #FFF;
            background-color: #2196F3
        }

        a.badge-primary:focus, a.badge-primary:hover {
            color: #FFF;
            background-color: #0c7cd5
        }

        .badge-secondary {
            color: #FFF;
            background-color: #868e96
        }

        a.badge-secondary:focus, a.badge-secondary:hover {
            color: #FFF;
            background-color: #6c757d
        }

        .badge-success {
            color: #FFF;
            background-color: #32c787
        }

        a.badge-success:focus, a.badge-success:hover {
            color: #FFF;
            background-color: #289e6b
        }

        .badge-info {
            color: #FFF;
            background-color: #03A9F4
        }

        a.badge-info:focus, a.badge-info:hover {
            color: #FFF;
            background-color: #0286c2
        }

        .badge-warning {
            color: #FFF;
            background-color: #ffc721
        }

        a.badge-warning:focus, a.badge-warning:hover {
            color: #FFF;
            background-color: #edb100
        }

        .badge-danger {
            color: #FFF;
            background-color: #ff6b68
        }

        a.badge-danger:focus, a.badge-danger:hover {
            color: #FFF;
            background-color: #ff3935
        }

        .badge-light {
            color: #525a62;
            background-color: #f6f6f6
        }

        a.badge-light:focus, a.badge-light:hover {
            color: #525a62;
            background-color: #dddcdc
        }

        .badge-dark {
            color: #FFF;
            background-color: #495057
        }

        a.badge-dark:focus, a.badge-dark:hover {
            color: #FFF;
            background-color: #32373b
        }

        .jumbotron {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            background-color: #FFF;
            border-radius: 2px
        }

        @media (min-width: 576px) {
            .jumbotron {
                padding: 4rem 2rem
            }
        }

        .jumbotron-fluid {
            padding-right: 0;
            padding-left: 0;
            border-radius: 0
        }

        .alert {
            padding: 1.1rem 1.5rem;
            margin-bottom: 1rem;
            border: 0 solid transparent;
            border-radius: 2px
        }

        .alert-heading {
            color: inherit
        }

        .alert-dismissible {
            padding-right: 4.5rem
        }

        .alert-dismissible .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 1.1rem 1.5rem;
            color: inherit
        }

        .alert-primary {
            color: #fff;
            background-color: #2196f3;
            border-color: #c1e2fc
        }

        .alert-primary hr {
            border-top-color: #a9d7fb
        }

        .alert-primary .alert-link {
            color: #e6e5e5
        }

        .alert-secondary {
            color: #fff;
            background-color: #868e96;
            border-color: #dddfe2
        }

        .alert-secondary hr {
            border-top-color: #cfd2d6
        }

        .alert-secondary .alert-link {
            color: #e6e5e5
        }

        .alert-success {
            color: #fff;
            background-color: #32c787;
            border-color: #c6efdd
        }

        .alert-success hr {
            border-top-color: #b2e9d1
        }

        .alert-info {
            color: #fff;
            background-color: #03a9f4;
            border-color: #b8e7fc
        }

        .alert-info hr {
            border-top-color: #a0dffb
        }

        .alert-warning {
            color: #fff;
            background-color: #ffc721;
            border-color: #ffefc1
        }

        .alert-warning hr {
            border-top-color: #ffe8a8
        }

        .alert-danger {
            color: #fff;
            background-color: #ff6b68;
            border-color: #ffd6d5
        }

        .alert-danger hr {
            border-top-color: #ffbdbc
        }

        .alert-light {
            color: #fff;
            background-color: #f6f6f6;
            border-color: #fcfcfc
        }

        .alert-light hr {
            border-top-color: #efefef
        }

        .alert-light .alert-link {
            color: #e6e5e5
        }

        .alert-dark {
            color: #fff;
            background-color: #495057;
            border-color: #ccced0
        }

        .alert-dark hr {
            border-top-color: #bfc1c4
        }

        .alert-dark .alert-link {
            color: #e6e5e5
        }

        @-webkit-keyframes progress-bar-stripes {
            from {
                background-position: 5px 0
            }
            to {
                background-position: 0 0
            }
        }

        @keyframes progress-bar-stripes {
            from {
                background-position: 5px 0
            }
            to {
                background-position: 0 0
            }
        }

        .progress {
            display: flex;
            height: 5px;
            font-size: .75rem;
            background-color: #e9ecef;
            border-radius: 2px
        }

        .media, .progress-bar {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .progress-bar {
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            color: #2196F3;
            text-align: center;
            white-space: nowrap;
            background-color: #2196F3;
            transition: width .6s ease
        }

        .popover, .tooltip {
            font-family: Roboto, sans-serif;
            font-style: normal;
            text-transform: none;
            letter-spacing: normal;
            word-break: normal;
            word-spacing: normal;
            white-space: normal;
            line-break: auto;
            word-wrap: break-word;
            text-shadow: none;
            text-decoration: none
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .progress-bar {
                transition: none
            }
        }

        .progress-bar-striped {
            background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
            background-size: 5px 5px
        }

        .progress-bar-animated {
            -webkit-animation: progress-bar-stripes 1s linear infinite;
            animation: progress-bar-stripes 1s linear infinite
        }

        .media {
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start
        }

        .media-body {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1
        }

        .list-group {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0
        }

        .list-group-item-action {
            width: 100%;
            color: #495057;
            text-align: inherit
        }

        .list-group-item-action:focus, .list-group-item-action:hover {
            color: #495057;
            text-decoration: none;
            background-color: #000
        }

        .list-group-item-action:active {
            color: #747a80;
            background-color: #e9ecef
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 1rem 2rem;
            margin-bottom: 0;
            background-color: transparent;
            border: 0 solid rgba(0, 0, 0, .125)
        }

        .list-group-item:first-child {
            border-top-left-radius: 2px;
            border-top-right-radius: 2px
        }

        .list-group-item:last-child {
            margin-bottom: 0;
            border-bottom-right-radius: 2px;
            border-bottom-left-radius: 2px
        }

        .list-group-item:focus, .list-group-item:hover {
            z-index: 1;
            text-decoration: none
        }

        .list-group-item.disabled, .list-group-item:disabled {
            color: #868e96;
            pointer-events: none;
            background-color: transparent
        }

        .list-group-item.active {
            z-index: 2;
            color: #FFF;
            background-color: #2196F3;
            border-color: #2196F3
        }

        .list-group-flush .list-group-item {
            border-right: 0;
            border-left: 0;
            border-radius: 0
        }

        .list-group-flush .list-group-item:last-child {
            margin-bottom: 0
        }

        .list-group-flush:first-child .list-group-item:first-child {
            border-top: 0
        }

        .list-group-flush:last-child .list-group-item:last-child {
            margin-bottom: 0;
            border-bottom: 0
        }

        .list-group-item-primary {
            color: #114e7e;
            background-color: #c1e2fc
        }

        .list-group-item-primary.list-group-item-action:focus, .list-group-item-primary.list-group-item-action:hover {
            color: #114e7e;
            background-color: #a9d7fb
        }

        .list-group-item-primary.list-group-item-action.active {
            color: #FFF;
            background-color: #114e7e;
            border-color: #114e7e
        }

        .list-group-item-secondary {
            color: #464a4e;
            background-color: #dddfe2
        }

        .list-group-item-secondary.list-group-item-action:focus, .list-group-item-secondary.list-group-item-action:hover {
            color: #464a4e;
            background-color: #cfd2d6
        }

        .list-group-item-secondary.list-group-item-action.active {
            color: #FFF;
            background-color: #464a4e;
            border-color: #464a4e
        }

        .list-group-item-success {
            color: #1a6746;
            background-color: #c6efdd
        }

        .list-group-item-success.list-group-item-action:focus, .list-group-item-success.list-group-item-action:hover {
            color: #1a6746;
            background-color: #b2e9d1
        }

        .list-group-item-success.list-group-item-action.active {
            color: #FFF;
            background-color: #1a6746;
            border-color: #1a6746
        }

        .list-group-item-info {
            color: #02587f;
            background-color: #b8e7fc
        }

        .list-group-item-info.list-group-item-action:focus, .list-group-item-info.list-group-item-action:hover {
            color: #02587f;
            background-color: #a0dffb
        }

        .list-group-item-info.list-group-item-action.active {
            color: #FFF;
            background-color: #02587f;
            border-color: #02587f
        }

        .list-group-item-warning {
            color: #856711;
            background-color: #ffefc1
        }

        .list-group-item-warning.list-group-item-action:focus, .list-group-item-warning.list-group-item-action:hover {
            color: #856711;
            background-color: #ffe8a8
        }

        .list-group-item-warning.list-group-item-action.active {
            color: #FFF;
            background-color: #856711;
            border-color: #856711
        }

        .list-group-item-danger {
            color: #853836;
            background-color: #ffd6d5
        }

        .list-group-item-danger.list-group-item-action:focus, .list-group-item-danger.list-group-item-action:hover {
            color: #853836;
            background-color: #ffbdbc
        }

        .list-group-item-danger.list-group-item-action.active {
            color: #FFF;
            background-color: #853836;
            border-color: #853836
        }

        .list-group-item-light {
            color: gray;
            background-color: #fcfcfc
        }

        .list-group-item-light.list-group-item-action:focus, .list-group-item-light.list-group-item-action:hover {
            color: gray;
            background-color: #efefef
        }

        .list-group-item-light.list-group-item-action.active {
            color: #FFF;
            background-color: gray;
            border-color: gray
        }

        .list-group-item-dark {
            color: #262a2d;
            background-color: #ccced0
        }

        .list-group-item-dark.list-group-item-action:focus, .list-group-item-dark.list-group-item-action:hover {
            color: #262a2d;
            background-color: #bfc1c4
        }

        .list-group-item-dark.list-group-item-action.active {
            color: #FFF;
            background-color: #262a2d;
            border-color: #262a2d
        }

        .close {
            float: right;
            font-size: 1.5rem;
            color: #000;
            text-shadow: none
        }

        .modal-title, .popover, .tooltip {
            line-height: 1.5
        }

        .close:hover {
            color: #000;
            text-decoration: none
        }

        .close:not(:disabled):not(.disabled) {
            cursor: pointer
        }

        .close:not(:disabled):not(.disabled):focus, .close:not(:disabled):not(.disabled):hover {
            opacity: .75
        }

        button.close {
            padding: 0;
            background-color: transparent;
            border: 0;
            appearance: none
        }

        .toast, .toast-header {
            background-color: rgba(255, 255, 255, .85);
            background-clip: padding-box
        }

        a.close.disabled {
            pointer-events: none
        }

        .toast {
            max-width: 350px;
            font-size: .875rem;
            border: 1px solid rgba(0, 0, 0, .1);
            border-radius: .25rem;
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .1);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            opacity: 0
        }

        .toast:not(:last-child) {
            margin-bottom: .75rem
        }

        .toast.showing {
            opacity: 1
        }

        .toast.show {
            display: block;
            opacity: 1
        }

        .toast.hide {
            display: none
        }

        .toast-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: .25rem .75rem;
            color: #868e96;
            border-bottom: 1px solid rgba(0, 0, 0, .05)
        }

        .toast-body {
            padding: .75rem
        }

        .modal-open .modal {
            overflow-x: hidden;
            overflow-y: auto
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            display: none;
            width: 100%;
            height: 100%;
            overflow: hidden;
            outline: 0
        }

        .modal-dialog {
            position: relative;
            width: auto;
            margin: .5rem;
            pointer-events: none
        }

        .modal.fade .modal-dialog {
            transition: -webkit-transform .3s ease-out;
            transition: transform .3s ease-out;
            transition: transform .3s ease-out, -webkit-transform .3s ease-out;
            -webkit-transform: translate(0, -50px);
            transform: translate(0, -50px)
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .modal.fade .modal-dialog {
                transition: none
            }
        }

        .modal.show .modal-dialog {
            -webkit-transform: none;
            transform: none
        }

        .modal-dialog-centered {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            min-height: calc(100% - (.5rem * 2))
        }

        .modal-dialog-centered::before {
            display: block;
            height: calc(100vh - (.5rem * 2))
        }

        .modal-content, .modal-header {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .modal-content {
            position: relative;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background-color: #FFF;
            background-clip: padding-box;
            border: 0 solid rgba(0, 0, 0, .2);
            border-radius: 2px;
            outline: 0
        }

        .flex-column, .flex-row {
            -webkit-box-direction: normal !important
        }

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            width: 100vw;
            height: 100vh;
            background-color: #000
        }

        .modal-backdrop.fade {
            opacity: 0
        }

        .modal-backdrop.show {
            opacity: .2
        }

        .modal-header {
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 25px 30px 0;
            border-bottom: 0 solid #e9ecef;
            border-top-left-radius: 2px;
            border-top-right-radius: 2px
        }

        .modal-header .close {
            padding: 25px 30px 0;
            margin: -1rem -1rem -1rem auto
        }

        .modal-title {
            margin-bottom: 0
        }

        .modal-body {
            position: relative;
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 25px 30px
        }

        .modal-footer {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            padding: 25px 30px;
            border-top: 0 solid #e9ecef;
            border-bottom-right-radius: 2px;
            border-bottom-left-radius: 2px
        }

        .popover, .popover .arrow, .popover .arrow::after, .popover .arrow::before, .tooltip, .tooltip .arrow {
            position: absolute;
            display: block
        }

        .modal-footer > :not(:first-child) {
            margin-left: .25rem
        }

        .modal-footer > :not(:last-child) {
            margin-right: .25rem
        }

        .modal-scrollbar-measure {
            position: absolute;
            top: -9999px;
            width: 50px;
            height: 50px;
            overflow: scroll
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto
            }

            .modal-dialog-centered {
                min-height: calc(100% - (1.75rem * 2))
            }

            .modal-dialog-centered::before {
                height: calc(100vh - (1.75rem * 2))
            }

            .modal-sm {
                max-width: 300px
            }
        }

        @media (min-width: 992px) {
            .modal-lg, .modal-xl {
                max-width: 1000px
            }
        }

        @media (min-width: 1200px) {
            .modal-xl {
                max-width: 1140px
            }
        }

        .tooltip {
            z-index: 1070;
            margin: 0;
            text-align: left;
            text-align: start;
            opacity: 0
        }

        .tooltip.show {
            opacity: 1
        }

        .tooltip .arrow {
            width: .8rem;
            height: .4rem
        }

        .tooltip .arrow::before {
            position: absolute;
            border-color: transparent;
            border-style: solid
        }

        .bs-tooltip-auto[x-placement^=top], .bs-tooltip-top {
            padding: .4rem 0
        }

        .bs-tooltip-auto[x-placement^=top] .arrow, .bs-tooltip-top .arrow {
            bottom: 0
        }

        .bs-tooltip-auto[x-placement^=top] .arrow::before, .bs-tooltip-top .arrow::before {
            top: 0;
            border-width: .4rem .4rem 0;
            border-top-color: #495057
        }

        .bs-tooltip-auto[x-placement^=right], .bs-tooltip-right {
            padding: 0 .4rem
        }

        .bs-tooltip-auto[x-placement^=right] .arrow, .bs-tooltip-right .arrow {
            left: 0;
            width: .4rem;
            height: .8rem
        }

        .bs-tooltip-auto[x-placement^=right] .arrow::before, .bs-tooltip-right .arrow::before {
            right: 0;
            border-width: .4rem .4rem .4rem 0;
            border-right-color: #495057
        }

        .bs-tooltip-auto[x-placement^=bottom], .bs-tooltip-bottom {
            padding: .4rem 0
        }

        .bs-tooltip-auto[x-placement^=bottom] .arrow, .bs-tooltip-bottom .arrow {
            top: 0
        }

        .bs-tooltip-auto[x-placement^=bottom] .arrow::before, .bs-tooltip-bottom .arrow::before {
            bottom: 0;
            border-width: 0 .4rem .4rem;
            border-bottom-color: #495057
        }

        .bs-tooltip-auto[x-placement^=left], .bs-tooltip-left {
            padding: 0 .4rem
        }

        .bs-tooltip-auto[x-placement^=left] .arrow, .bs-tooltip-left .arrow {
            right: 0;
            width: .4rem;
            height: .8rem
        }

        .bs-tooltip-auto[x-placement^=left] .arrow::before, .bs-tooltip-left .arrow::before {
            left: 0;
            border-width: .4rem 0 .4rem .4rem;
            border-left-color: #495057
        }

        .tooltip-inner {
            max-width: 200px;
            padding: .7rem 1.1rem;
            color: #FFF;
            text-align: center;
            background-color: #495057;
            border-radius: 2px
        }

        .popover {
            top: 0;
            left: 0;
            z-index: 1060;
            max-width: 276px;
            text-align: left;
            text-align: start;
            background-color: #FFF;
            background-clip: padding-box;
            border: 1px solid transparent;
            border-radius: 2px
        }

        .popover .arrow {
            width: 1rem;
            height: .5rem;
            margin: 0 2px
        }

        .popover .arrow::after, .popover .arrow::before {
            border-color: transparent;
            border-style: solid
        }

        .bs-popover-auto[x-placement^=top], .bs-popover-top {
            margin-bottom: .5rem
        }

        .bs-popover-auto[x-placement^=top] .arrow, .bs-popover-top .arrow {
            bottom: calc((.5rem + 1px) * -1)
        }

        .bs-popover-auto[x-placement^=top] .arrow::after, .bs-popover-auto[x-placement^=top] .arrow::before, .bs-popover-top .arrow::after, .bs-popover-top .arrow::before {
            border-width: .5rem .5rem 0
        }

        .bs-popover-auto[x-placement^=top] .arrow::before, .bs-popover-top .arrow::before {
            bottom: 0;
            border-top-color: rgba(0, 0, 0, .05)
        }

        .bs-popover-auto[x-placement^=top] .arrow::after, .bs-popover-top .arrow::after {
            bottom: 1px;
            border-top-color: #FFF
        }

        .bs-popover-auto[x-placement^=right], .bs-popover-right {
            margin-left: .5rem
        }

        .bs-popover-auto[x-placement^=right] .arrow, .bs-popover-right .arrow {
            left: calc((.5rem + 1px) * -1);
            width: .5rem;
            height: 1rem;
            margin: 2px 0
        }

        .bs-popover-auto[x-placement^=right] .arrow::after, .bs-popover-auto[x-placement^=right] .arrow::before, .bs-popover-right .arrow::after, .bs-popover-right .arrow::before {
            border-width: .5rem .5rem .5rem 0
        }

        .bs-popover-auto[x-placement^=right] .arrow::before, .bs-popover-right .arrow::before {
            left: 0;
            border-right-color: rgba(0, 0, 0, .05)
        }

        .bs-popover-auto[x-placement^=right] .arrow::after, .bs-popover-right .arrow::after {
            left: 1px;
            border-right-color: #FFF
        }

        .bs-popover-auto[x-placement^=bottom], .bs-popover-bottom {
            margin-top: .5rem
        }

        .bs-popover-auto[x-placement^=bottom] .arrow, .bs-popover-bottom .arrow {
            top: calc((.5rem + 1px) * -1)
        }

        .bs-popover-auto[x-placement^=bottom] .arrow::after, .bs-popover-auto[x-placement^=bottom] .arrow::before, .bs-popover-bottom .arrow::after, .bs-popover-bottom .arrow::before {
            border-width: 0 .5rem .5rem
        }

        .bs-popover-auto[x-placement^=bottom] .arrow::before, .bs-popover-bottom .arrow::before {
            top: 0;
            border-bottom-color: rgba(0, 0, 0, .05)
        }

        .bs-popover-auto[x-placement^=bottom] .arrow::after, .bs-popover-bottom .arrow::after {
            top: 1px;
            border-bottom-color: #FFF
        }

        .bs-popover-auto[x-placement^=bottom] .popover-header::before, .bs-popover-bottom .popover-header::before {
            position: absolute;
            top: 0;
            left: 50%;
            display: block;
            width: 1rem;
            margin-left: -.5rem;
            content: "";
            border-bottom: 1px solid #FFF
        }

        .carousel, .carousel-inner, .carousel-item {
            position: relative
        }

        .bs-popover-auto[x-placement^=left], .bs-popover-left {
            margin-right: .5rem
        }

        .bs-popover-auto[x-placement^=left] .arrow, .bs-popover-left .arrow {
            right: calc((.5rem + 1px) * -1);
            width: .5rem;
            height: 1rem;
            margin: 2px 0
        }

        .bs-popover-auto[x-placement^=left] .arrow::after, .bs-popover-auto[x-placement^=left] .arrow::before, .bs-popover-left .arrow::after, .bs-popover-left .arrow::before {
            border-width: .5rem 0 .5rem .5rem
        }

        .bs-popover-auto[x-placement^=left] .arrow::before, .bs-popover-left .arrow::before {
            right: 0;
            border-left-color: rgba(0, 0, 0, .05)
        }

        .bs-popover-auto[x-placement^=left] .arrow::after, .bs-popover-left .arrow::after {
            right: 1px;
            border-left-color: #FFF
        }

        .popover-header {
            padding: 1.25rem 1.5rem;
            font-size: 1rem;
            color: #333;
            background-color: #FFF;
            border-top-left-radius: calc(2px - 1px);
            border-top-right-radius: calc(2px - 1px)
        }

        .popover-header:empty {
            display: none
        }

        .popover-body {
            padding: 1.25rem 1.5rem;
            color: #747a80
        }

        .carousel.pointer-event {
            -ms-touch-action: pan-y;
            touch-action: pan-y
        }

        .carousel-inner {
            width: 100%;
            overflow: hidden
        }

        .carousel-inner::after {
            display: block;
            clear: both
        }

        .carousel-item {
            display: none;
            float: left;
            width: 100%;
            margin-right: -100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transition: -webkit-transform .6s ease-in-out;
            transition: transform .6s ease-in-out;
            transition: transform .6s ease-in-out, -webkit-transform .6s ease-in-out
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .carousel-item {
                transition: none
            }
        }

        .carousel-item-next, .carousel-item-prev, .carousel-item.active {
            display: block
        }

        .active.carousel-item-right, .carousel-item-next:not(.carousel-item-left) {
            -webkit-transform: translateX(100%);
            transform: translateX(100%)
        }

        .active.carousel-item-left, .carousel-item-prev:not(.carousel-item-right) {
            -webkit-transform: translateX(-100%);
            transform: translateX(-100%)
        }

        .carousel-fade .carousel-item {
            opacity: 0;
            transition-property: opacity;
            -webkit-transform: none;
            transform: none
        }

        .carousel-fade .carousel-item-next.carousel-item-left, .carousel-fade .carousel-item-prev.carousel-item-right, .carousel-fade .carousel-item.active {
            z-index: 1;
            opacity: 1
        }

        .carousel-fade .active.carousel-item-left, .carousel-fade .active.carousel-item-right {
            z-index: 0;
            opacity: 0;
            transition: 0s .6s opacity
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .carousel-fade .active.carousel-item-left, .carousel-fade .active.carousel-item-right {
                transition: none
            }
        }

        .carousel-control-next, .carousel-control-prev {
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            width: 15%;
            color: #FFF;
            text-align: center;
            opacity: .8;
            transition: opacity .15s ease
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .carousel-control-next, .carousel-control-prev {
                transition: none
            }
        }

        .carousel-control-next:focus, .carousel-control-next:hover, .carousel-control-prev:focus, .carousel-control-prev:hover {
            color: #FFF;
            text-decoration: none;
            outline: 0;
            opacity: .9
        }

        .carousel-control-prev {
            left: 0
        }

        .carousel-control-next {
            right: 0
        }

        .carousel-control-next-icon, .carousel-control-prev-icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: center center no-repeat;
            background-size: 100% 100%
        }

        .btn--action, .carousel-indicators {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .carousel-indicators {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 15;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            padding-left: 0;
            margin-right: 15%;
            margin-left: 15%;
            list-style: none
        }

        .spinner-border, .spinner-grow {
            display: inline-block;
            vertical-align: text-bottom
        }

        .carousel-indicators li {
            box-sizing: content-box;
            -webkit-box-flex: 0;
            -ms-flex: 0 1 auto;
            flex: 0 1 auto;
            width: 30px;
            height: 3px;
            margin-right: 3px;
            margin-left: 3px;
            text-indent: -999px;
            cursor: pointer;
            background-color: #FFF;
            background-clip: padding-box;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            opacity: .5;
            transition: opacity .6s ease
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .carousel-indicators li {
                transition: none
            }
        }

        .carousel-indicators .active {
            opacity: 1
        }

        .carousel-caption {
            position: absolute;
            right: 15%;
            left: 15%;
            z-index: 10;
            padding-top: 20px;
            color: rgba(255, 255, 255, .9);
            text-align: center
        }

        @-webkit-keyframes spinner-border {
            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        @keyframes spinner-border {
            to {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        .spinner-border {
            width: 2rem;
            height: 2rem;
            border: .25em solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner-border .75s linear infinite;
            animation: spinner-border .75s linear infinite
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: .2em
        }

        @-webkit-keyframes spinner-grow {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0)
            }
            50% {
                opacity: 1
            }
        }

        @keyframes spinner-grow {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0)
            }
            50% {
                opacity: 1
            }
        }

        .spinner-grow {
            width: 2rem;
            height: 2rem;
            background-color: currentColor;
            border-radius: 50%;
            opacity: 0;
            -webkit-animation: spinner-grow .75s linear infinite;
            animation: spinner-grow .75s linear infinite
        }

        .dropdown-menu, .login__block {
            -webkit-animation-duration: .3s
        }

        .spinner-grow-sm {
            width: 1rem;
            height: 1rem
        }

        .align-baseline {
            vertical-align: baseline !important
        }

        .align-top {
            vertical-align: top !important
        }

        .align-middle {
            vertical-align: middle !important
        }

        .align-bottom {
            vertical-align: bottom !important
        }

        .align-text-bottom {
            vertical-align: text-bottom !important
        }

        .align-text-top {
            vertical-align: text-top !important
        }

        .bg-primary {
            background-color: #2196F3 !important
        }

        a.bg-primary:focus, a.bg-primary:hover, button.bg-primary:focus, button.bg-primary:hover {
            background-color: #0c7cd5 !important
        }

        .bg-secondary {
            background-color: #868e96 !important
        }

        a.bg-secondary:focus, a.bg-secondary:hover, button.bg-secondary:focus, button.bg-secondary:hover {
            background-color: #6c757d !important
        }

        .bg-success {
            background-color: #32c787 !important
        }

        a.bg-success:focus, a.bg-success:hover, button.bg-success:focus, button.bg-success:hover {
            background-color: #289e6b !important
        }

        .bg-info {
            background-color: #03A9F4 !important
        }

        a.bg-info:focus, a.bg-info:hover, button.bg-info:focus, button.bg-info:hover {
            background-color: #0286c2 !important
        }

        .bg-warning {
            background-color: #ffc721 !important
        }

        a.bg-warning:focus, a.bg-warning:hover, button.bg-warning:focus, button.bg-warning:hover {
            background-color: #edb100 !important
        }

        .bg-danger {
            background-color: #ff6b68 !important
        }

        a.bg-danger:focus, a.bg-danger:hover, button.bg-danger:focus, button.bg-danger:hover {
            background-color: #ff3935 !important
        }

        .bg-light {
            background-color: #f6f6f6 !important
        }

        a.bg-light:focus, a.bg-light:hover, button.bg-light:focus, button.bg-light:hover {
            background-color: #dddcdc !important
        }

        .bg-dark {
            background-color: #495057 !important
        }

        a.bg-dark:focus, a.bg-dark:hover, button.bg-dark:focus, button.bg-dark:hover {
            background-color: #32373b !important
        }

        .bg-transparent {
            background-color: transparent !important
        }

        .border {
            border: 1px solid #dee2e6 !important
        }

        .border-top {
            border-top: 1px solid #dee2e6 !important
        }

        .border-right {
            border-right: 1px solid #dee2e6 !important
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important
        }

        .border-left {
            border-left: 1px solid #dee2e6 !important
        }

        .border-0 {
            border: 0 !important
        }

        .rounded-right, .rounded-top {
            border-top-right-radius: 2px !important
        }

        .rounded-bottom, .rounded-right {
            border-bottom-right-radius: 2px !important
        }

        .rounded-left, .rounded-top {
            border-top-left-radius: 2px !important
        }

        .rounded-bottom, .rounded-left {
            border-bottom-left-radius: 2px !important
        }

        .border-top-0 {
            border-top: 0 !important
        }

        .border-right-0 {
            border-right: 0 !important
        }

        .border-bottom-0 {
            border-bottom: 0 !important
        }

        .border-left-0 {
            border-left: 0 !important
        }

        .border-primary {
            border-color: #2196F3 !important
        }

        .border-secondary {
            border-color: #868e96 !important
        }

        .border-success {
            border-color: #32c787 !important
        }

        .border-info {
            border-color: #03A9F4 !important
        }

        .border-warning {
            border-color: #ffc721 !important
        }

        .border-danger {
            border-color: #ff6b68 !important
        }

        .border-light {
            border-color: #f6f6f6 !important
        }

        .border-dark {
            border-color: #495057 !important
        }

        .border-white {
            border-color: #FFF !important
        }

        .rounded {
            border-radius: 2px !important
        }

        .rounded-circle {
            border-radius: 50% !important
        }

        .rounded-pill {
            border-radius: 50rem !important
        }

        .rounded-0 {
            border-radius: 0 !important
        }

        .clearfix::after {
            display: block;
            clear: both
        }

        .d-none {
            display: none !important
        }

        .d-inline {
            display: inline !important
        }

        .d-inline-block {
            display: inline-block !important
        }

        .d-block {
            display: block !important
        }

        .d-table {
            display: table !important
        }

        .d-table-row {
            display: table-row !important
        }

        .d-table-cell {
            display: table-cell !important
        }

        .d-flex {
            display: -webkit-box !important;
            display: -ms-flexbox !important;
            display: flex !important
        }

        .d-inline-flex {
            display: -webkit-inline-box !important;
            display: -ms-inline-flexbox !important;
            display: inline-flex !important
        }

        @media (min-width: 576px) {
            .d-sm-none {
                display: none !important
            }

            .d-sm-inline {
                display: inline !important
            }

            .d-sm-inline-block {
                display: inline-block !important
            }

            .d-sm-block {
                display: block !important
            }

            .d-sm-table {
                display: table !important
            }

            .d-sm-table-row {
                display: table-row !important
            }

            .d-sm-table-cell {
                display: table-cell !important
            }

            .d-sm-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important
            }

            .d-sm-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important
            }
        }

        @media (min-width: 768px) {
            .d-md-none {
                display: none !important
            }

            .d-md-inline {
                display: inline !important
            }

            .d-md-inline-block {
                display: inline-block !important
            }

            .d-md-block {
                display: block !important
            }

            .d-md-table {
                display: table !important
            }

            .d-md-table-row {
                display: table-row !important
            }

            .d-md-table-cell {
                display: table-cell !important
            }

            .d-md-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important
            }

            .d-md-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important
            }
        }

        @media (min-width: 992px) {
            .d-lg-none {
                display: none !important
            }

            .d-lg-inline {
                display: inline !important
            }

            .d-lg-inline-block {
                display: inline-block !important
            }

            .d-lg-block {
                display: block !important
            }

            .d-lg-table {
                display: table !important
            }

            .d-lg-table-row {
                display: table-row !important
            }

            .d-lg-table-cell {
                display: table-cell !important
            }

            .d-lg-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important
            }

            .d-lg-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important
            }
        }

        @media (min-width: 1200px) {
            .d-xl-none {
                display: none !important
            }

            .d-xl-inline {
                display: inline !important
            }

            .d-xl-inline-block {
                display: inline-block !important
            }

            .d-xl-block {
                display: block !important
            }

            .d-xl-table {
                display: table !important
            }

            .d-xl-table-row {
                display: table-row !important
            }

            .d-xl-table-cell {
                display: table-cell !important
            }

            .d-xl-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important
            }

            .d-xl-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important
            }
        }

        @media print {
            .d-print-none {
                display: none !important
            }

            .d-print-inline {
                display: inline !important
            }

            .d-print-inline-block {
                display: inline-block !important
            }

            .d-print-block {
                display: block !important
            }

            .d-print-table {
                display: table !important
            }

            .d-print-table-row {
                display: table-row !important
            }

            .d-print-table-cell {
                display: table-cell !important
            }

            .d-print-flex {
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important
            }

            .d-print-inline-flex {
                display: -webkit-inline-box !important;
                display: -ms-inline-flexbox !important;
                display: inline-flex !important
            }
        }

        .embed-responsive {
            position: relative;
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden
        }

        .embed-responsive::before {
            display: block
        }

        .embed-responsive .embed-responsive-item, .embed-responsive embed, .embed-responsive iframe, .embed-responsive object, .embed-responsive video {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0
        }

        .embed-responsive-21by9::before {
            padding-top: 42.8571428571%
        }

        .embed-responsive-16by9::before {
            padding-top: 56.25%
        }

        .embed-responsive-3by4::before {
            padding-top: 133.333333333%
        }

        .embed-responsive-1by1::before {
            padding-top: 100%
        }

        .flex-row {
            -webkit-box-orient: horizontal !important;
            -ms-flex-direction: row !important;
            flex-direction: row !important
        }

        .flex-column {
            -webkit-box-orient: vertical !important;
            -ms-flex-direction: column !important;
            flex-direction: column !important
        }

        .flex-column-reverse, .flex-row-reverse {
            -webkit-box-direction: reverse !important
        }

        .flex-row-reverse {
            -webkit-box-orient: horizontal !important;
            -ms-flex-direction: row-reverse !important;
            flex-direction: row-reverse !important
        }

        .flex-column-reverse {
            -webkit-box-orient: vertical !important;
            -ms-flex-direction: column-reverse !important;
            flex-direction: column-reverse !important
        }

        .flex-wrap {
            -ms-flex-wrap: wrap !important;
            flex-wrap: wrap !important
        }

        .flex-nowrap {
            -ms-flex-wrap: nowrap !important;
            flex-wrap: nowrap !important
        }

        .flex-wrap-reverse {
            -ms-flex-wrap: wrap-reverse !important;
            flex-wrap: wrap-reverse !important
        }

        .flex-fill {
            -webkit-box-flex: 1 !important;
            -ms-flex: 1 1 auto !important;
            flex: 1 1 auto !important
        }

        .flex-grow-0 {
            -webkit-box-flex: 0 !important;
            -ms-flex-positive: 0 !important;
            flex-grow: 0 !important
        }

        .flex-grow-1 {
            -webkit-box-flex: 1 !important;
            -ms-flex-positive: 1 !important;
            flex-grow: 1 !important
        }

        .flex-shrink-0 {
            -ms-flex-negative: 0 !important;
            flex-shrink: 0 !important
        }

        .flex-shrink-1 {
            -ms-flex-negative: 1 !important;
            flex-shrink: 1 !important
        }

        .justify-content-start {
            -webkit-box-pack: start !important;
            -ms-flex-pack: start !important;
            justify-content: flex-start !important
        }

        .justify-content-end {
            -webkit-box-pack: end !important;
            -ms-flex-pack: end !important;
            justify-content: flex-end !important
        }

        .justify-content-center {
            -webkit-box-pack: center !important;
            -ms-flex-pack: center !important;
            justify-content: center !important
        }

        .justify-content-between {
            -webkit-box-pack: justify !important;
            -ms-flex-pack: justify !important;
            justify-content: space-between !important
        }

        .justify-content-around {
            -ms-flex-pack: distribute !important;
            justify-content: space-around !important
        }

        .align-items-start {
            -webkit-box-align: start !important;
            -ms-flex-align: start !important;
            align-items: flex-start !important
        }

        .align-items-end {
            -webkit-box-align: end !important;
            -ms-flex-align: end !important;
            align-items: flex-end !important
        }

        .align-items-center {
            -webkit-box-align: center !important;
            -ms-flex-align: center !important;
            align-items: center !important
        }

        .align-items-baseline {
            -webkit-box-align: baseline !important;
            -ms-flex-align: baseline !important;
            align-items: baseline !important
        }

        .align-items-stretch {
            -webkit-box-align: stretch !important;
            -ms-flex-align: stretch !important;
            align-items: stretch !important
        }

        .align-content-start {
            -ms-flex-line-pack: start !important;
            align-content: flex-start !important
        }

        .align-content-end {
            -ms-flex-line-pack: end !important;
            align-content: flex-end !important
        }

        .align-content-center {
            -ms-flex-line-pack: center !important;
            align-content: center !important
        }

        .align-content-between {
            -ms-flex-line-pack: justify !important;
            align-content: space-between !important
        }

        .align-content-around {
            -ms-flex-line-pack: distribute !important;
            align-content: space-around !important
        }

        .align-content-stretch {
            -ms-flex-line-pack: stretch !important;
            align-content: stretch !important
        }

        .align-self-auto {
            -ms-flex-item-align: auto !important;
            -ms-grid-row-align: auto !important;
            align-self: auto !important
        }

        .align-self-start {
            -ms-flex-item-align: start !important;
            align-self: flex-start !important
        }

        .align-self-end {
            -ms-flex-item-align: end !important;
            align-self: flex-end !important
        }

        .align-self-center {
            -ms-flex-item-align: center !important;
            -ms-grid-row-align: center !important;
            align-self: center !important
        }

        .align-self-baseline {
            -ms-flex-item-align: baseline !important;
            align-self: baseline !important
        }

        .align-self-stretch {
            -ms-flex-item-align: stretch !important;
            -ms-grid-row-align: stretch !important;
            align-self: stretch !important
        }

        @media (min-width: 576px) {
            .flex-sm-column, .flex-sm-row {
                -webkit-box-direction: normal !important
            }

            .flex-sm-row {
                -webkit-box-orient: horizontal !important;
                -ms-flex-direction: row !important;
                flex-direction: row !important
            }

            .flex-sm-column {
                -webkit-box-orient: vertical !important;
                -ms-flex-direction: column !important;
                flex-direction: column !important
            }

            .flex-sm-row-reverse {
                -webkit-box-orient: horizontal !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: row-reverse !important;
                flex-direction: row-reverse !important
            }

            .flex-sm-column-reverse {
                -webkit-box-orient: vertical !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: column-reverse !important;
                flex-direction: column-reverse !important
            }

            .flex-sm-wrap {
                -ms-flex-wrap: wrap !important;
                flex-wrap: wrap !important
            }

            .flex-sm-nowrap {
                -ms-flex-wrap: nowrap !important;
                flex-wrap: nowrap !important
            }

            .flex-sm-wrap-reverse {
                -ms-flex-wrap: wrap-reverse !important;
                flex-wrap: wrap-reverse !important
            }

            .flex-sm-fill {
                -webkit-box-flex: 1 !important;
                -ms-flex: 1 1 auto !important;
                flex: 1 1 auto !important
            }

            .flex-sm-grow-0 {
                -webkit-box-flex: 0 !important;
                -ms-flex-positive: 0 !important;
                flex-grow: 0 !important
            }

            .flex-sm-grow-1 {
                -webkit-box-flex: 1 !important;
                -ms-flex-positive: 1 !important;
                flex-grow: 1 !important
            }

            .flex-sm-shrink-0 {
                -ms-flex-negative: 0 !important;
                flex-shrink: 0 !important
            }

            .flex-sm-shrink-1 {
                -ms-flex-negative: 1 !important;
                flex-shrink: 1 !important
            }

            .justify-content-sm-start {
                -webkit-box-pack: start !important;
                -ms-flex-pack: start !important;
                justify-content: flex-start !important
            }

            .justify-content-sm-end {
                -webkit-box-pack: end !important;
                -ms-flex-pack: end !important;
                justify-content: flex-end !important
            }

            .justify-content-sm-center {
                -webkit-box-pack: center !important;
                -ms-flex-pack: center !important;
                justify-content: center !important
            }

            .justify-content-sm-between {
                -webkit-box-pack: justify !important;
                -ms-flex-pack: justify !important;
                justify-content: space-between !important
            }

            .justify-content-sm-around {
                -ms-flex-pack: distribute !important;
                justify-content: space-around !important
            }

            .align-items-sm-start {
                -webkit-box-align: start !important;
                -ms-flex-align: start !important;
                align-items: flex-start !important
            }

            .align-items-sm-end {
                -webkit-box-align: end !important;
                -ms-flex-align: end !important;
                align-items: flex-end !important
            }

            .align-items-sm-center {
                -webkit-box-align: center !important;
                -ms-flex-align: center !important;
                align-items: center !important
            }

            .align-items-sm-baseline {
                -webkit-box-align: baseline !important;
                -ms-flex-align: baseline !important;
                align-items: baseline !important
            }

            .align-items-sm-stretch {
                -webkit-box-align: stretch !important;
                -ms-flex-align: stretch !important;
                align-items: stretch !important
            }

            .align-content-sm-start {
                -ms-flex-line-pack: start !important;
                align-content: flex-start !important
            }

            .align-content-sm-end {
                -ms-flex-line-pack: end !important;
                align-content: flex-end !important
            }

            .align-content-sm-center {
                -ms-flex-line-pack: center !important;
                align-content: center !important
            }

            .align-content-sm-between {
                -ms-flex-line-pack: justify !important;
                align-content: space-between !important
            }

            .align-content-sm-around {
                -ms-flex-line-pack: distribute !important;
                align-content: space-around !important
            }

            .align-content-sm-stretch {
                -ms-flex-line-pack: stretch !important;
                align-content: stretch !important
            }

            .align-self-sm-auto {
                -ms-flex-item-align: auto !important;
                -ms-grid-row-align: auto !important;
                align-self: auto !important
            }

            .align-self-sm-start {
                -ms-flex-item-align: start !important;
                align-self: flex-start !important
            }

            .align-self-sm-end {
                -ms-flex-item-align: end !important;
                align-self: flex-end !important
            }

            .align-self-sm-center {
                -ms-flex-item-align: center !important;
                -ms-grid-row-align: center !important;
                align-self: center !important
            }

            .align-self-sm-baseline {
                -ms-flex-item-align: baseline !important;
                align-self: baseline !important
            }

            .align-self-sm-stretch {
                -ms-flex-item-align: stretch !important;
                -ms-grid-row-align: stretch !important;
                align-self: stretch !important
            }
        }

        @media (min-width: 768px) {
            .flex-md-column, .flex-md-row {
                -webkit-box-direction: normal !important
            }

            .flex-md-row {
                -webkit-box-orient: horizontal !important;
                -ms-flex-direction: row !important;
                flex-direction: row !important
            }

            .flex-md-column {
                -webkit-box-orient: vertical !important;
                -ms-flex-direction: column !important;
                flex-direction: column !important
            }

            .flex-md-row-reverse {
                -webkit-box-orient: horizontal !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: row-reverse !important;
                flex-direction: row-reverse !important
            }

            .flex-md-column-reverse {
                -webkit-box-orient: vertical !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: column-reverse !important;
                flex-direction: column-reverse !important
            }

            .flex-md-wrap {
                -ms-flex-wrap: wrap !important;
                flex-wrap: wrap !important
            }

            .flex-md-nowrap {
                -ms-flex-wrap: nowrap !important;
                flex-wrap: nowrap !important
            }

            .flex-md-wrap-reverse {
                -ms-flex-wrap: wrap-reverse !important;
                flex-wrap: wrap-reverse !important
            }

            .flex-md-fill {
                -webkit-box-flex: 1 !important;
                -ms-flex: 1 1 auto !important;
                flex: 1 1 auto !important
            }

            .flex-md-grow-0 {
                -webkit-box-flex: 0 !important;
                -ms-flex-positive: 0 !important;
                flex-grow: 0 !important
            }

            .flex-md-grow-1 {
                -webkit-box-flex: 1 !important;
                -ms-flex-positive: 1 !important;
                flex-grow: 1 !important
            }

            .flex-md-shrink-0 {
                -ms-flex-negative: 0 !important;
                flex-shrink: 0 !important
            }

            .flex-md-shrink-1 {
                -ms-flex-negative: 1 !important;
                flex-shrink: 1 !important
            }

            .justify-content-md-start {
                -webkit-box-pack: start !important;
                -ms-flex-pack: start !important;
                justify-content: flex-start !important
            }

            .justify-content-md-end {
                -webkit-box-pack: end !important;
                -ms-flex-pack: end !important;
                justify-content: flex-end !important
            }

            .justify-content-md-center {
                -webkit-box-pack: center !important;
                -ms-flex-pack: center !important;
                justify-content: center !important
            }

            .justify-content-md-between {
                -webkit-box-pack: justify !important;
                -ms-flex-pack: justify !important;
                justify-content: space-between !important
            }

            .justify-content-md-around {
                -ms-flex-pack: distribute !important;
                justify-content: space-around !important
            }

            .align-items-md-start {
                -webkit-box-align: start !important;
                -ms-flex-align: start !important;
                align-items: flex-start !important
            }

            .align-items-md-end {
                -webkit-box-align: end !important;
                -ms-flex-align: end !important;
                align-items: flex-end !important
            }

            .align-items-md-center {
                -webkit-box-align: center !important;
                -ms-flex-align: center !important;
                align-items: center !important
            }

            .align-items-md-baseline {
                -webkit-box-align: baseline !important;
                -ms-flex-align: baseline !important;
                align-items: baseline !important
            }

            .align-items-md-stretch {
                -webkit-box-align: stretch !important;
                -ms-flex-align: stretch !important;
                align-items: stretch !important
            }

            .align-content-md-start {
                -ms-flex-line-pack: start !important;
                align-content: flex-start !important
            }

            .align-content-md-end {
                -ms-flex-line-pack: end !important;
                align-content: flex-end !important
            }

            .align-content-md-center {
                -ms-flex-line-pack: center !important;
                align-content: center !important
            }

            .align-content-md-between {
                -ms-flex-line-pack: justify !important;
                align-content: space-between !important
            }

            .align-content-md-around {
                -ms-flex-line-pack: distribute !important;
                align-content: space-around !important
            }

            .align-content-md-stretch {
                -ms-flex-line-pack: stretch !important;
                align-content: stretch !important
            }

            .align-self-md-auto {
                -ms-flex-item-align: auto !important;
                -ms-grid-row-align: auto !important;
                align-self: auto !important
            }

            .align-self-md-start {
                -ms-flex-item-align: start !important;
                align-self: flex-start !important
            }

            .align-self-md-end {
                -ms-flex-item-align: end !important;
                align-self: flex-end !important
            }

            .align-self-md-center {
                -ms-flex-item-align: center !important;
                -ms-grid-row-align: center !important;
                align-self: center !important
            }

            .align-self-md-baseline {
                -ms-flex-item-align: baseline !important;
                align-self: baseline !important
            }

            .align-self-md-stretch {
                -ms-flex-item-align: stretch !important;
                -ms-grid-row-align: stretch !important;
                align-self: stretch !important
            }
        }

        @media (min-width: 992px) {
            .flex-lg-column, .flex-lg-row {
                -webkit-box-direction: normal !important
            }

            .flex-lg-row {
                -webkit-box-orient: horizontal !important;
                -ms-flex-direction: row !important;
                flex-direction: row !important
            }

            .flex-lg-column {
                -webkit-box-orient: vertical !important;
                -ms-flex-direction: column !important;
                flex-direction: column !important
            }

            .flex-lg-row-reverse {
                -webkit-box-orient: horizontal !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: row-reverse !important;
                flex-direction: row-reverse !important
            }

            .flex-lg-column-reverse {
                -webkit-box-orient: vertical !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: column-reverse !important;
                flex-direction: column-reverse !important
            }

            .flex-lg-wrap {
                -ms-flex-wrap: wrap !important;
                flex-wrap: wrap !important
            }

            .flex-lg-nowrap {
                -ms-flex-wrap: nowrap !important;
                flex-wrap: nowrap !important
            }

            .flex-lg-wrap-reverse {
                -ms-flex-wrap: wrap-reverse !important;
                flex-wrap: wrap-reverse !important
            }

            .flex-lg-fill {
                -webkit-box-flex: 1 !important;
                -ms-flex: 1 1 auto !important;
                flex: 1 1 auto !important
            }

            .flex-lg-grow-0 {
                -webkit-box-flex: 0 !important;
                -ms-flex-positive: 0 !important;
                flex-grow: 0 !important
            }

            .flex-lg-grow-1 {
                -webkit-box-flex: 1 !important;
                -ms-flex-positive: 1 !important;
                flex-grow: 1 !important
            }

            .flex-lg-shrink-0 {
                -ms-flex-negative: 0 !important;
                flex-shrink: 0 !important
            }

            .flex-lg-shrink-1 {
                -ms-flex-negative: 1 !important;
                flex-shrink: 1 !important
            }

            .justify-content-lg-start {
                -webkit-box-pack: start !important;
                -ms-flex-pack: start !important;
                justify-content: flex-start !important
            }

            .justify-content-lg-end {
                -webkit-box-pack: end !important;
                -ms-flex-pack: end !important;
                justify-content: flex-end !important
            }

            .justify-content-lg-center {
                -webkit-box-pack: center !important;
                -ms-flex-pack: center !important;
                justify-content: center !important
            }

            .justify-content-lg-between {
                -webkit-box-pack: justify !important;
                -ms-flex-pack: justify !important;
                justify-content: space-between !important
            }

            .justify-content-lg-around {
                -ms-flex-pack: distribute !important;
                justify-content: space-around !important
            }

            .align-items-lg-start {
                -webkit-box-align: start !important;
                -ms-flex-align: start !important;
                align-items: flex-start !important
            }

            .align-items-lg-end {
                -webkit-box-align: end !important;
                -ms-flex-align: end !important;
                align-items: flex-end !important
            }

            .align-items-lg-center {
                -webkit-box-align: center !important;
                -ms-flex-align: center !important;
                align-items: center !important
            }

            .align-items-lg-baseline {
                -webkit-box-align: baseline !important;
                -ms-flex-align: baseline !important;
                align-items: baseline !important
            }

            .align-items-lg-stretch {
                -webkit-box-align: stretch !important;
                -ms-flex-align: stretch !important;
                align-items: stretch !important
            }

            .align-content-lg-start {
                -ms-flex-line-pack: start !important;
                align-content: flex-start !important
            }

            .align-content-lg-end {
                -ms-flex-line-pack: end !important;
                align-content: flex-end !important
            }

            .align-content-lg-center {
                -ms-flex-line-pack: center !important;
                align-content: center !important
            }

            .align-content-lg-between {
                -ms-flex-line-pack: justify !important;
                align-content: space-between !important
            }

            .align-content-lg-around {
                -ms-flex-line-pack: distribute !important;
                align-content: space-around !important
            }

            .align-content-lg-stretch {
                -ms-flex-line-pack: stretch !important;
                align-content: stretch !important
            }

            .align-self-lg-auto {
                -ms-flex-item-align: auto !important;
                -ms-grid-row-align: auto !important;
                align-self: auto !important
            }

            .align-self-lg-start {
                -ms-flex-item-align: start !important;
                align-self: flex-start !important
            }

            .align-self-lg-end {
                -ms-flex-item-align: end !important;
                align-self: flex-end !important
            }

            .align-self-lg-center {
                -ms-flex-item-align: center !important;
                -ms-grid-row-align: center !important;
                align-self: center !important
            }

            .align-self-lg-baseline {
                -ms-flex-item-align: baseline !important;
                align-self: baseline !important
            }

            .align-self-lg-stretch {
                -ms-flex-item-align: stretch !important;
                -ms-grid-row-align: stretch !important;
                align-self: stretch !important
            }
        }

        @media (min-width: 1200px) {
            .flex-xl-column, .flex-xl-row {
                -webkit-box-direction: normal !important
            }

            .flex-xl-row {
                -webkit-box-orient: horizontal !important;
                -ms-flex-direction: row !important;
                flex-direction: row !important
            }

            .flex-xl-column {
                -webkit-box-orient: vertical !important;
                -ms-flex-direction: column !important;
                flex-direction: column !important
            }

            .flex-xl-row-reverse {
                -webkit-box-orient: horizontal !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: row-reverse !important;
                flex-direction: row-reverse !important
            }

            .flex-xl-column-reverse {
                -webkit-box-orient: vertical !important;
                -webkit-box-direction: reverse !important;
                -ms-flex-direction: column-reverse !important;
                flex-direction: column-reverse !important
            }

            .flex-xl-wrap {
                -ms-flex-wrap: wrap !important;
                flex-wrap: wrap !important
            }

            .flex-xl-nowrap {
                -ms-flex-wrap: nowrap !important;
                flex-wrap: nowrap !important
            }

            .flex-xl-wrap-reverse {
                -ms-flex-wrap: wrap-reverse !important;
                flex-wrap: wrap-reverse !important
            }

            .flex-xl-fill {
                -webkit-box-flex: 1 !important;
                -ms-flex: 1 1 auto !important;
                flex: 1 1 auto !important
            }

            .flex-xl-grow-0 {
                -webkit-box-flex: 0 !important;
                -ms-flex-positive: 0 !important;
                flex-grow: 0 !important
            }

            .flex-xl-grow-1 {
                -webkit-box-flex: 1 !important;
                -ms-flex-positive: 1 !important;
                flex-grow: 1 !important
            }

            .flex-xl-shrink-0 {
                -ms-flex-negative: 0 !important;
                flex-shrink: 0 !important
            }

            .flex-xl-shrink-1 {
                -ms-flex-negative: 1 !important;
                flex-shrink: 1 !important
            }

            .justify-content-xl-start {
                -webkit-box-pack: start !important;
                -ms-flex-pack: start !important;
                justify-content: flex-start !important
            }

            .justify-content-xl-end {
                -webkit-box-pack: end !important;
                -ms-flex-pack: end !important;
                justify-content: flex-end !important
            }

            .justify-content-xl-center {
                -webkit-box-pack: center !important;
                -ms-flex-pack: center !important;
                justify-content: center !important
            }

            .justify-content-xl-between {
                -webkit-box-pack: justify !important;
                -ms-flex-pack: justify !important;
                justify-content: space-between !important
            }

            .justify-content-xl-around {
                -ms-flex-pack: distribute !important;
                justify-content: space-around !important
            }

            .align-items-xl-start {
                -webkit-box-align: start !important;
                -ms-flex-align: start !important;
                align-items: flex-start !important
            }

            .align-items-xl-end {
                -webkit-box-align: end !important;
                -ms-flex-align: end !important;
                align-items: flex-end !important
            }

            .align-items-xl-center {
                -webkit-box-align: center !important;
                -ms-flex-align: center !important;
                align-items: center !important
            }

            .align-items-xl-baseline {
                -webkit-box-align: baseline !important;
                -ms-flex-align: baseline !important;
                align-items: baseline !important
            }

            .align-items-xl-stretch {
                -webkit-box-align: stretch !important;
                -ms-flex-align: stretch !important;
                align-items: stretch !important
            }

            .align-content-xl-start {
                -ms-flex-line-pack: start !important;
                align-content: flex-start !important
            }

            .align-content-xl-end {
                -ms-flex-line-pack: end !important;
                align-content: flex-end !important
            }

            .align-content-xl-center {
                -ms-flex-line-pack: center !important;
                align-content: center !important
            }

            .align-content-xl-between {
                -ms-flex-line-pack: justify !important;
                align-content: space-between !important
            }

            .align-content-xl-around {
                -ms-flex-line-pack: distribute !important;
                align-content: space-around !important
            }

            .align-content-xl-stretch {
                -ms-flex-line-pack: stretch !important;
                align-content: stretch !important
            }

            .align-self-xl-auto {
                -ms-flex-item-align: auto !important;
                -ms-grid-row-align: auto !important;
                align-self: auto !important
            }

            .align-self-xl-start {
                -ms-flex-item-align: start !important;
                align-self: flex-start !important
            }

            .align-self-xl-end {
                -ms-flex-item-align: end !important;
                align-self: flex-end !important
            }

            .align-self-xl-center {
                -ms-flex-item-align: center !important;
                -ms-grid-row-align: center !important;
                align-self: center !important
            }

            .align-self-xl-baseline {
                -ms-flex-item-align: baseline !important;
                align-self: baseline !important
            }

            .align-self-xl-stretch {
                -ms-flex-item-align: stretch !important;
                -ms-grid-row-align: stretch !important;
                align-self: stretch !important
            }
        }

        .messages, .toolbar {
            -webkit-box-orient: horizontal
        }

        .btn--action, .icon-toggle {
            -webkit-box-align: center;
            cursor: pointer
        }

        .float-left {
            float: left !important
        }

        .float-right {
            float: right !important
        }

        .float-none {
            float: none !important
        }

        @media (min-width: 576px) {
            .float-sm-left {
                float: left !important
            }

            .float-sm-right {
                float: right !important
            }

            .float-sm-none {
                float: none !important
            }
        }

        @media (min-width: 768px) {
            .float-md-left {
                float: left !important
            }

            .float-md-right {
                float: right !important
            }

            .float-md-none {
                float: none !important
            }
        }

        @media (min-width: 992px) {
            .float-lg-left {
                float: left !important
            }

            .float-lg-right {
                float: right !important
            }

            .float-lg-none {
                float: none !important
            }
        }

        .overflow-auto {
            overflow: auto !important
        }

        .overflow-hidden {
            overflow: hidden !important
        }

        .position-static {
            position: static !important
        }

        .position-relative {
            position: relative !important
        }

        .position-absolute {
            position: absolute !important
        }

        .position-fixed {
            position: fixed !important
        }

        .position-sticky {
            position: -webkit-sticky !important;
            position: sticky !important
        }

        .fixed-bottom, .fixed-top {
            position: fixed;
            right: 0;
            z-index: 1030;
            left: 0
        }

        .fixed-top {
            top: 0
        }

        .fixed-bottom {
            bottom: 0
        }

        @supports ((position:-webkit-sticky) or (position:sticky)) {
            .sticky-top {
                position: -webkit-sticky;
                position: sticky;
                top: 0;
                z-index: 1020
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0
        }

        .sr-only-focusable:active, .sr-only-focusable:focus {
            position: static;
            width: auto;
            height: auto;
            overflow: visible;
            clip: auto;
            white-space: normal
        }

        .shadow-sm {
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075) !important
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important
        }

        .shadow-lg {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important
        }

        .shadow-none {
            box-shadow: none !important
        }

        .w-25 {
            width: 25% !important
        }

        .w-50 {
            width: 50% !important
        }

        .w-75 {
            width: 75% !important
        }

        .w-100 {
            width: 100% !important
        }

        .w-auto {
            width: auto !important
        }

        .h-25 {
            height: 25% !important
        }

        .h-50 {
            height: 50% !important
        }

        .h-75 {
            height: 75% !important
        }

        .h-100 {
            height: 100% !important
        }

        .h-auto {
            height: auto !important
        }

        .mw-100 {
            max-width: 100% !important
        }

        .mh-100 {
            max-height: 100% !important
        }

        .min-vw-100 {
            min-width: 100vw !important
        }

        .min-vh-100 {
            min-height: 100vh !important
        }

        .vw-100 {
            width: 100vw !important
        }

        .vh-100 {
            height: 100vh !important
        }

        .m-0 {
            margin: 0 !important
        }

        .mt-0, .my-0 {
            margin-top: 0 !important
        }

        .mr-0, .mx-0 {
            margin-right: 0 !important
        }

        .mb-0, .my-0 {
            margin-bottom: 0 !important
        }

        .ml-0, .mx-0 {
            margin-left: 0 !important
        }

        .m-1 {
            margin: .25rem !important
        }

        .mt-1, .my-1 {
            margin-top: .25rem !important
        }

        .mr-1, .mx-1 {
            margin-right: .25rem !important
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem !important
        }

        .ml-1, .mx-1 {
            margin-left: .25rem !important
        }

        .m-2 {
            margin: .5rem !important
        }

        .mt-2, .my-2 {
            margin-top: .5rem !important
        }

        .mr-2, .mx-2 {
            margin-right: .5rem !important
        }

        .mb-2, .my-2 {
            margin-bottom: .5rem !important
        }

        .ml-2, .mx-2 {
            margin-left: .5rem !important
        }

        .m-3 {
            margin: 1rem !important
        }

        .mt-3, .my-3 {
            margin-top: 1rem !important
        }

        .mr-3, .mx-3 {
            margin-right: 1rem !important
        }

        .mb-3, .my-3 {
            margin-bottom: 1rem !important
        }

        .ml-3, .mx-3 {
            margin-left: 1rem !important
        }

        .m-4 {
            margin: 1.5rem !important
        }

        .mt-4, .my-4 {
            margin-top: 1.5rem !important
        }

        .mr-4, .mx-4 {
            margin-right: 1.5rem !important
        }

        .mb-4, .my-4 {
            margin-bottom: 1.5rem !important
        }

        .ml-4, .mx-4 {
            margin-left: 1.5rem !important
        }

        .m-5 {
            margin: 3rem !important
        }

        .mt-5, .my-5 {
            margin-top: 3rem !important
        }

        .mr-5, .mx-5 {
            margin-right: 3rem !important
        }

        .mb-5, .my-5 {
            margin-bottom: 3rem !important
        }

        .ml-5, .mx-5 {
            margin-left: 3rem !important
        }

        .p-0 {
            padding: 0 !important
        }

        .pt-0, .py-0 {
            padding-top: 0 !important
        }

        .pr-0, .px-0 {
            padding-right: 0 !important
        }

        .pb-0, .py-0 {
            padding-bottom: 0 !important
        }

        .pl-0, .px-0 {
            padding-left: 0 !important
        }

        .p-1 {
            padding: .25rem !important
        }

        .pt-1, .py-1 {
            padding-top: .25rem !important
        }

        .pr-1, .px-1 {
            padding-right: .25rem !important
        }

        .pb-1, .py-1 {
            padding-bottom: .25rem !important
        }

        .pl-1, .px-1 {
            padding-left: .25rem !important
        }

        .p-2 {
            padding: .5rem !important
        }

        .pt-2, .py-2 {
            padding-top: .5rem !important
        }

        .pr-2, .px-2 {
            padding-right: .5rem !important
        }

        .pb-2, .py-2 {
            padding-bottom: .5rem !important
        }

        .pl-2, .px-2 {
            padding-left: .5rem !important
        }

        .p-3 {
            padding: 1rem !important
        }

        .pt-3, .py-3 {
            padding-top: 1rem !important
        }

        .pr-3, .px-3 {
            padding-right: 1rem !important
        }

        .pb-3, .py-3 {
            padding-bottom: 1rem !important
        }

        .pl-3, .px-3 {
            padding-left: 1rem !important
        }

        .p-4 {
            padding: 1.5rem !important
        }

        .pt-4, .py-4 {
            padding-top: 1.5rem !important
        }

        .pr-4, .px-4 {
            padding-right: 1.5rem !important
        }

        .pb-4, .py-4 {
            padding-bottom: 1.5rem !important
        }

        .pl-4, .px-4 {
            padding-left: 1.5rem !important
        }

        .p-5 {
            padding: 3rem !important
        }

        .pt-5, .py-5 {
            padding-top: 3rem !important
        }

        .pr-5, .px-5 {
            padding-right: 3rem !important
        }

        .pb-5, .py-5 {
            padding-bottom: 3rem !important
        }

        .pl-5, .px-5 {
            padding-left: 3rem !important
        }

        .m-n1 {
            margin: -.25rem !important
        }

        .mt-n1, .my-n1 {
            margin-top: -.25rem !important
        }

        .mr-n1, .mx-n1 {
            margin-right: -.25rem !important
        }

        .mb-n1, .my-n1 {
            margin-bottom: -.25rem !important
        }

        .ml-n1, .mx-n1 {
            margin-left: -.25rem !important
        }

        .m-n2 {
            margin: -.5rem !important
        }

        .mt-n2, .my-n2 {
            margin-top: -.5rem !important
        }

        .mr-n2, .mx-n2 {
            margin-right: -.5rem !important
        }

        .mb-n2, .my-n2 {
            margin-bottom: -.5rem !important
        }

        .ml-n2, .mx-n2 {
            margin-left: -.5rem !important
        }

        .m-n3 {
            margin: -1rem !important
        }

        .mt-n3, .my-n3 {
            margin-top: -1rem !important
        }

        .mr-n3, .mx-n3 {
            margin-right: -1rem !important
        }

        .mb-n3, .my-n3 {
            margin-bottom: -1rem !important
        }

        .ml-n3, .mx-n3 {
            margin-left: -1rem !important
        }

        .m-n4 {
            margin: -1.5rem !important
        }

        .mt-n4, .my-n4 {
            margin-top: -1.5rem !important
        }

        .mr-n4, .mx-n4 {
            margin-right: -1.5rem !important
        }

        .mb-n4, .my-n4 {
            margin-bottom: -1.5rem !important
        }

        .ml-n4, .mx-n4 {
            margin-left: -1.5rem !important
        }

        .m-n5 {
            margin: -3rem !important
        }

        .mt-n5, .my-n5 {
            margin-top: -3rem !important
        }

        .mr-n5, .mx-n5 {
            margin-right: -3rem !important
        }

        .mb-n5, .my-n5 {
            margin-bottom: -3rem !important
        }

        .ml-n5, .mx-n5 {
            margin-left: -3rem !important
        }

        .m-auto {
            margin: auto !important
        }

        .mt-auto, .my-auto {
            margin-top: auto !important
        }

        .mr-auto, .mx-auto {
            margin-right: auto !important
        }

        .mb-auto, .my-auto {
            margin-bottom: auto !important
        }

        .ml-auto, .mx-auto {
            margin-left: auto !important
        }

        @media (min-width: 576px) {
            .m-sm-0 {
                margin: 0 !important
            }

            .mt-sm-0, .my-sm-0 {
                margin-top: 0 !important
            }

            .mr-sm-0, .mx-sm-0 {
                margin-right: 0 !important
            }

            .mb-sm-0, .my-sm-0 {
                margin-bottom: 0 !important
            }

            .ml-sm-0, .mx-sm-0 {
                margin-left: 0 !important
            }

            .m-sm-1 {
                margin: .25rem !important
            }

            .mt-sm-1, .my-sm-1 {
                margin-top: .25rem !important
            }

            .mr-sm-1, .mx-sm-1 {
                margin-right: .25rem !important
            }

            .mb-sm-1, .my-sm-1 {
                margin-bottom: .25rem !important
            }

            .ml-sm-1, .mx-sm-1 {
                margin-left: .25rem !important
            }

            .m-sm-2 {
                margin: .5rem !important
            }

            .mt-sm-2, .my-sm-2 {
                margin-top: .5rem !important
            }

            .mr-sm-2, .mx-sm-2 {
                margin-right: .5rem !important
            }

            .mb-sm-2, .my-sm-2 {
                margin-bottom: .5rem !important
            }

            .ml-sm-2, .mx-sm-2 {
                margin-left: .5rem !important
            }

            .m-sm-3 {
                margin: 1rem !important
            }

            .mt-sm-3, .my-sm-3 {
                margin-top: 1rem !important
            }

            .mr-sm-3, .mx-sm-3 {
                margin-right: 1rem !important
            }

            .mb-sm-3, .my-sm-3 {
                margin-bottom: 1rem !important
            }

            .ml-sm-3, .mx-sm-3 {
                margin-left: 1rem !important
            }

            .m-sm-4 {
                margin: 1.5rem !important
            }

            .mt-sm-4, .my-sm-4 {
                margin-top: 1.5rem !important
            }

            .mr-sm-4, .mx-sm-4 {
                margin-right: 1.5rem !important
            }

            .mb-sm-4, .my-sm-4 {
                margin-bottom: 1.5rem !important
            }

            .ml-sm-4, .mx-sm-4 {
                margin-left: 1.5rem !important
            }

            .m-sm-5 {
                margin: 3rem !important
            }

            .mt-sm-5, .my-sm-5 {
                margin-top: 3rem !important
            }

            .mr-sm-5, .mx-sm-5 {
                margin-right: 3rem !important
            }

            .mb-sm-5, .my-sm-5 {
                margin-bottom: 3rem !important
            }

            .ml-sm-5, .mx-sm-5 {
                margin-left: 3rem !important
            }

            .p-sm-0 {
                padding: 0 !important
            }

            .pt-sm-0, .py-sm-0 {
                padding-top: 0 !important
            }

            .pr-sm-0, .px-sm-0 {
                padding-right: 0 !important
            }

            .pb-sm-0, .py-sm-0 {
                padding-bottom: 0 !important
            }

            .pl-sm-0, .px-sm-0 {
                padding-left: 0 !important
            }

            .p-sm-1 {
                padding: .25rem !important
            }

            .pt-sm-1, .py-sm-1 {
                padding-top: .25rem !important
            }

            .pr-sm-1, .px-sm-1 {
                padding-right: .25rem !important
            }

            .pb-sm-1, .py-sm-1 {
                padding-bottom: .25rem !important
            }

            .pl-sm-1, .px-sm-1 {
                padding-left: .25rem !important
            }

            .p-sm-2 {
                padding: .5rem !important
            }

            .pt-sm-2, .py-sm-2 {
                padding-top: .5rem !important
            }

            .pr-sm-2, .px-sm-2 {
                padding-right: .5rem !important
            }

            .pb-sm-2, .py-sm-2 {
                padding-bottom: .5rem !important
            }

            .pl-sm-2, .px-sm-2 {
                padding-left: .5rem !important
            }

            .p-sm-3 {
                padding: 1rem !important
            }

            .pt-sm-3, .py-sm-3 {
                padding-top: 1rem !important
            }

            .pr-sm-3, .px-sm-3 {
                padding-right: 1rem !important
            }

            .pb-sm-3, .py-sm-3 {
                padding-bottom: 1rem !important
            }

            .pl-sm-3, .px-sm-3 {
                padding-left: 1rem !important
            }

            .p-sm-4 {
                padding: 1.5rem !important
            }

            .pt-sm-4, .py-sm-4 {
                padding-top: 1.5rem !important
            }

            .pr-sm-4, .px-sm-4 {
                padding-right: 1.5rem !important
            }

            .pb-sm-4, .py-sm-4 {
                padding-bottom: 1.5rem !important
            }

            .pl-sm-4, .px-sm-4 {
                padding-left: 1.5rem !important
            }

            .p-sm-5 {
                padding: 3rem !important
            }

            .pt-sm-5, .py-sm-5 {
                padding-top: 3rem !important
            }

            .pr-sm-5, .px-sm-5 {
                padding-right: 3rem !important
            }

            .pb-sm-5, .py-sm-5 {
                padding-bottom: 3rem !important
            }

            .pl-sm-5, .px-sm-5 {
                padding-left: 3rem !important
            }

            .m-sm-n1 {
                margin: -.25rem !important
            }

            .mt-sm-n1, .my-sm-n1 {
                margin-top: -.25rem !important
            }

            .mr-sm-n1, .mx-sm-n1 {
                margin-right: -.25rem !important
            }

            .mb-sm-n1, .my-sm-n1 {
                margin-bottom: -.25rem !important
            }

            .ml-sm-n1, .mx-sm-n1 {
                margin-left: -.25rem !important
            }

            .m-sm-n2 {
                margin: -.5rem !important
            }

            .mt-sm-n2, .my-sm-n2 {
                margin-top: -.5rem !important
            }

            .mr-sm-n2, .mx-sm-n2 {
                margin-right: -.5rem !important
            }

            .mb-sm-n2, .my-sm-n2 {
                margin-bottom: -.5rem !important
            }

            .ml-sm-n2, .mx-sm-n2 {
                margin-left: -.5rem !important
            }

            .m-sm-n3 {
                margin: -1rem !important
            }

            .mt-sm-n3, .my-sm-n3 {
                margin-top: -1rem !important
            }

            .mr-sm-n3, .mx-sm-n3 {
                margin-right: -1rem !important
            }

            .mb-sm-n3, .my-sm-n3 {
                margin-bottom: -1rem !important
            }

            .ml-sm-n3, .mx-sm-n3 {
                margin-left: -1rem !important
            }

            .m-sm-n4 {
                margin: -1.5rem !important
            }

            .mt-sm-n4, .my-sm-n4 {
                margin-top: -1.5rem !important
            }

            .mr-sm-n4, .mx-sm-n4 {
                margin-right: -1.5rem !important
            }

            .mb-sm-n4, .my-sm-n4 {
                margin-bottom: -1.5rem !important
            }

            .ml-sm-n4, .mx-sm-n4 {
                margin-left: -1.5rem !important
            }

            .m-sm-n5 {
                margin: -3rem !important
            }

            .mt-sm-n5, .my-sm-n5 {
                margin-top: -3rem !important
            }

            .mr-sm-n5, .mx-sm-n5 {
                margin-right: -3rem !important
            }

            .mb-sm-n5, .my-sm-n5 {
                margin-bottom: -3rem !important
            }

            .ml-sm-n5, .mx-sm-n5 {
                margin-left: -3rem !important
            }

            .m-sm-auto {
                margin: auto !important
            }

            .mt-sm-auto, .my-sm-auto {
                margin-top: auto !important
            }

            .mr-sm-auto, .mx-sm-auto {
                margin-right: auto !important
            }

            .mb-sm-auto, .my-sm-auto {
                margin-bottom: auto !important
            }

            .ml-sm-auto, .mx-sm-auto {
                margin-left: auto !important
            }
        }

        @media (min-width: 768px) {
            .m-md-0 {
                margin: 0 !important
            }

            .mt-md-0, .my-md-0 {
                margin-top: 0 !important
            }

            .mr-md-0, .mx-md-0 {
                margin-right: 0 !important
            }

            .mb-md-0, .my-md-0 {
                margin-bottom: 0 !important
            }

            .ml-md-0, .mx-md-0 {
                margin-left: 0 !important
            }

            .m-md-1 {
                margin: .25rem !important
            }

            .mt-md-1, .my-md-1 {
                margin-top: .25rem !important
            }

            .mr-md-1, .mx-md-1 {
                margin-right: .25rem !important
            }

            .mb-md-1, .my-md-1 {
                margin-bottom: .25rem !important
            }

            .ml-md-1, .mx-md-1 {
                margin-left: .25rem !important
            }

            .m-md-2 {
                margin: .5rem !important
            }

            .mt-md-2, .my-md-2 {
                margin-top: .5rem !important
            }

            .mr-md-2, .mx-md-2 {
                margin-right: .5rem !important
            }

            .mb-md-2, .my-md-2 {
                margin-bottom: .5rem !important
            }

            .ml-md-2, .mx-md-2 {
                margin-left: .5rem !important
            }

            .m-md-3 {
                margin: 1rem !important
            }

            .mt-md-3, .my-md-3 {
                margin-top: 1rem !important
            }

            .mr-md-3, .mx-md-3 {
                margin-right: 1rem !important
            }

            .mb-md-3, .my-md-3 {
                margin-bottom: 1rem !important
            }

            .ml-md-3, .mx-md-3 {
                margin-left: 1rem !important
            }

            .m-md-4 {
                margin: 1.5rem !important
            }

            .mt-md-4, .my-md-4 {
                margin-top: 1.5rem !important
            }

            .mr-md-4, .mx-md-4 {
                margin-right: 1.5rem !important
            }

            .mb-md-4, .my-md-4 {
                margin-bottom: 1.5rem !important
            }

            .ml-md-4, .mx-md-4 {
                margin-left: 1.5rem !important
            }

            .m-md-5 {
                margin: 3rem !important
            }

            .mt-md-5, .my-md-5 {
                margin-top: 3rem !important
            }

            .mr-md-5, .mx-md-5 {
                margin-right: 3rem !important
            }

            .mb-md-5, .my-md-5 {
                margin-bottom: 3rem !important
            }

            .ml-md-5, .mx-md-5 {
                margin-left: 3rem !important
            }

            .p-md-0 {
                padding: 0 !important
            }

            .pt-md-0, .py-md-0 {
                padding-top: 0 !important
            }

            .pr-md-0, .px-md-0 {
                padding-right: 0 !important
            }

            .pb-md-0, .py-md-0 {
                padding-bottom: 0 !important
            }

            .pl-md-0, .px-md-0 {
                padding-left: 0 !important
            }

            .p-md-1 {
                padding: .25rem !important
            }

            .pt-md-1, .py-md-1 {
                padding-top: .25rem !important
            }

            .pr-md-1, .px-md-1 {
                padding-right: .25rem !important
            }

            .pb-md-1, .py-md-1 {
                padding-bottom: .25rem !important
            }

            .pl-md-1, .px-md-1 {
                padding-left: .25rem !important
            }

            .p-md-2 {
                padding: .5rem !important
            }

            .pt-md-2, .py-md-2 {
                padding-top: .5rem !important
            }

            .pr-md-2, .px-md-2 {
                padding-right: .5rem !important
            }

            .pb-md-2, .py-md-2 {
                padding-bottom: .5rem !important
            }

            .pl-md-2, .px-md-2 {
                padding-left: .5rem !important
            }

            .p-md-3 {
                padding: 1rem !important
            }

            .pt-md-3, .py-md-3 {
                padding-top: 1rem !important
            }

            .pr-md-3, .px-md-3 {
                padding-right: 1rem !important
            }

            .pb-md-3, .py-md-3 {
                padding-bottom: 1rem !important
            }

            .pl-md-3, .px-md-3 {
                padding-left: 1rem !important
            }

            .p-md-4 {
                padding: 1.5rem !important
            }

            .pt-md-4, .py-md-4 {
                padding-top: 1.5rem !important
            }

            .pr-md-4, .px-md-4 {
                padding-right: 1.5rem !important
            }

            .pb-md-4, .py-md-4 {
                padding-bottom: 1.5rem !important
            }

            .pl-md-4, .px-md-4 {
                padding-left: 1.5rem !important
            }

            .p-md-5 {
                padding: 3rem !important
            }

            .pt-md-5, .py-md-5 {
                padding-top: 3rem !important
            }

            .pr-md-5, .px-md-5 {
                padding-right: 3rem !important
            }

            .pb-md-5, .py-md-5 {
                padding-bottom: 3rem !important
            }

            .pl-md-5, .px-md-5 {
                padding-left: 3rem !important
            }

            .m-md-n1 {
                margin: -.25rem !important
            }

            .mt-md-n1, .my-md-n1 {
                margin-top: -.25rem !important
            }

            .mr-md-n1, .mx-md-n1 {
                margin-right: -.25rem !important
            }

            .mb-md-n1, .my-md-n1 {
                margin-bottom: -.25rem !important
            }

            .ml-md-n1, .mx-md-n1 {
                margin-left: -.25rem !important
            }

            .m-md-n2 {
                margin: -.5rem !important
            }

            .mt-md-n2, .my-md-n2 {
                margin-top: -.5rem !important
            }

            .mr-md-n2, .mx-md-n2 {
                margin-right: -.5rem !important
            }

            .mb-md-n2, .my-md-n2 {
                margin-bottom: -.5rem !important
            }

            .ml-md-n2, .mx-md-n2 {
                margin-left: -.5rem !important
            }

            .m-md-n3 {
                margin: -1rem !important
            }

            .mt-md-n3, .my-md-n3 {
                margin-top: -1rem !important
            }

            .mr-md-n3, .mx-md-n3 {
                margin-right: -1rem !important
            }

            .mb-md-n3, .my-md-n3 {
                margin-bottom: -1rem !important
            }

            .ml-md-n3, .mx-md-n3 {
                margin-left: -1rem !important
            }

            .m-md-n4 {
                margin: -1.5rem !important
            }

            .mt-md-n4, .my-md-n4 {
                margin-top: -1.5rem !important
            }

            .mr-md-n4, .mx-md-n4 {
                margin-right: -1.5rem !important
            }

            .mb-md-n4, .my-md-n4 {
                margin-bottom: -1.5rem !important
            }

            .ml-md-n4, .mx-md-n4 {
                margin-left: -1.5rem !important
            }

            .m-md-n5 {
                margin: -3rem !important
            }

            .mt-md-n5, .my-md-n5 {
                margin-top: -3rem !important
            }

            .mr-md-n5, .mx-md-n5 {
                margin-right: -3rem !important
            }

            .mb-md-n5, .my-md-n5 {
                margin-bottom: -3rem !important
            }

            .ml-md-n5, .mx-md-n5 {
                margin-left: -3rem !important
            }

            .m-md-auto {
                margin: auto !important
            }

            .mt-md-auto, .my-md-auto {
                margin-top: auto !important
            }

            .mr-md-auto, .mx-md-auto {
                margin-right: auto !important
            }

            .mb-md-auto, .my-md-auto {
                margin-bottom: auto !important
            }

            .ml-md-auto, .mx-md-auto {
                margin-left: auto !important
            }
        }

        @media (min-width: 992px) {
            .m-lg-0 {
                margin: 0 !important
            }

            .mt-lg-0, .my-lg-0 {
                margin-top: 0 !important
            }

            .mr-lg-0, .mx-lg-0 {
                margin-right: 0 !important
            }

            .mb-lg-0, .my-lg-0 {
                margin-bottom: 0 !important
            }

            .ml-lg-0, .mx-lg-0 {
                margin-left: 0 !important
            }

            .m-lg-1 {
                margin: .25rem !important
            }

            .mt-lg-1, .my-lg-1 {
                margin-top: .25rem !important
            }

            .mr-lg-1, .mx-lg-1 {
                margin-right: .25rem !important
            }

            .mb-lg-1, .my-lg-1 {
                margin-bottom: .25rem !important
            }

            .ml-lg-1, .mx-lg-1 {
                margin-left: .25rem !important
            }

            .m-lg-2 {
                margin: .5rem !important
            }

            .mt-lg-2, .my-lg-2 {
                margin-top: .5rem !important
            }

            .mr-lg-2, .mx-lg-2 {
                margin-right: .5rem !important
            }

            .mb-lg-2, .my-lg-2 {
                margin-bottom: .5rem !important
            }

            .ml-lg-2, .mx-lg-2 {
                margin-left: .5rem !important
            }

            .m-lg-3 {
                margin: 1rem !important
            }

            .mt-lg-3, .my-lg-3 {
                margin-top: 1rem !important
            }

            .mr-lg-3, .mx-lg-3 {
                margin-right: 1rem !important
            }

            .mb-lg-3, .my-lg-3 {
                margin-bottom: 1rem !important
            }

            .ml-lg-3, .mx-lg-3 {
                margin-left: 1rem !important
            }

            .m-lg-4 {
                margin: 1.5rem !important
            }

            .mt-lg-4, .my-lg-4 {
                margin-top: 1.5rem !important
            }

            .mr-lg-4, .mx-lg-4 {
                margin-right: 1.5rem !important
            }

            .mb-lg-4, .my-lg-4 {
                margin-bottom: 1.5rem !important
            }

            .ml-lg-4, .mx-lg-4 {
                margin-left: 1.5rem !important
            }

            .m-lg-5 {
                margin: 3rem !important
            }

            .mt-lg-5, .my-lg-5 {
                margin-top: 3rem !important
            }

            .mr-lg-5, .mx-lg-5 {
                margin-right: 3rem !important
            }

            .mb-lg-5, .my-lg-5 {
                margin-bottom: 3rem !important
            }

            .ml-lg-5, .mx-lg-5 {
                margin-left: 3rem !important
            }

            .p-lg-0 {
                padding: 0 !important
            }

            .pt-lg-0, .py-lg-0 {
                padding-top: 0 !important
            }

            .pr-lg-0, .px-lg-0 {
                padding-right: 0 !important
            }

            .pb-lg-0, .py-lg-0 {
                padding-bottom: 0 !important
            }

            .pl-lg-0, .px-lg-0 {
                padding-left: 0 !important
            }

            .p-lg-1 {
                padding: .25rem !important
            }

            .pt-lg-1, .py-lg-1 {
                padding-top: .25rem !important
            }

            .pr-lg-1, .px-lg-1 {
                padding-right: .25rem !important
            }

            .pb-lg-1, .py-lg-1 {
                padding-bottom: .25rem !important
            }

            .pl-lg-1, .px-lg-1 {
                padding-left: .25rem !important
            }

            .p-lg-2 {
                padding: .5rem !important
            }

            .pt-lg-2, .py-lg-2 {
                padding-top: .5rem !important
            }

            .pr-lg-2, .px-lg-2 {
                padding-right: .5rem !important
            }

            .pb-lg-2, .py-lg-2 {
                padding-bottom: .5rem !important
            }

            .pl-lg-2, .px-lg-2 {
                padding-left: .5rem !important
            }

            .p-lg-3 {
                padding: 1rem !important
            }

            .pt-lg-3, .py-lg-3 {
                padding-top: 1rem !important
            }

            .pr-lg-3, .px-lg-3 {
                padding-right: 1rem !important
            }

            .pb-lg-3, .py-lg-3 {
                padding-bottom: 1rem !important
            }

            .pl-lg-3, .px-lg-3 {
                padding-left: 1rem !important
            }

            .p-lg-4 {
                padding: 1.5rem !important
            }

            .pt-lg-4, .py-lg-4 {
                padding-top: 1.5rem !important
            }

            .pr-lg-4, .px-lg-4 {
                padding-right: 1.5rem !important
            }

            .pb-lg-4, .py-lg-4 {
                padding-bottom: 1.5rem !important
            }

            .pl-lg-4, .px-lg-4 {
                padding-left: 1.5rem !important
            }

            .p-lg-5 {
                padding: 3rem !important
            }

            .pt-lg-5, .py-lg-5 {
                padding-top: 3rem !important
            }

            .pr-lg-5, .px-lg-5 {
                padding-right: 3rem !important
            }

            .pb-lg-5, .py-lg-5 {
                padding-bottom: 3rem !important
            }

            .pl-lg-5, .px-lg-5 {
                padding-left: 3rem !important
            }

            .m-lg-n1 {
                margin: -.25rem !important
            }

            .mt-lg-n1, .my-lg-n1 {
                margin-top: -.25rem !important
            }

            .mr-lg-n1, .mx-lg-n1 {
                margin-right: -.25rem !important
            }

            .mb-lg-n1, .my-lg-n1 {
                margin-bottom: -.25rem !important
            }

            .ml-lg-n1, .mx-lg-n1 {
                margin-left: -.25rem !important
            }

            .m-lg-n2 {
                margin: -.5rem !important
            }

            .mt-lg-n2, .my-lg-n2 {
                margin-top: -.5rem !important
            }

            .mr-lg-n2, .mx-lg-n2 {
                margin-right: -.5rem !important
            }

            .mb-lg-n2, .my-lg-n2 {
                margin-bottom: -.5rem !important
            }

            .ml-lg-n2, .mx-lg-n2 {
                margin-left: -.5rem !important
            }

            .m-lg-n3 {
                margin: -1rem !important
            }

            .mt-lg-n3, .my-lg-n3 {
                margin-top: -1rem !important
            }

            .mr-lg-n3, .mx-lg-n3 {
                margin-right: -1rem !important
            }

            .mb-lg-n3, .my-lg-n3 {
                margin-bottom: -1rem !important
            }

            .ml-lg-n3, .mx-lg-n3 {
                margin-left: -1rem !important
            }

            .m-lg-n4 {
                margin: -1.5rem !important
            }

            .mt-lg-n4, .my-lg-n4 {
                margin-top: -1.5rem !important
            }

            .mr-lg-n4, .mx-lg-n4 {
                margin-right: -1.5rem !important
            }

            .mb-lg-n4, .my-lg-n4 {
                margin-bottom: -1.5rem !important
            }

            .ml-lg-n4, .mx-lg-n4 {
                margin-left: -1.5rem !important
            }

            .m-lg-n5 {
                margin: -3rem !important
            }

            .mt-lg-n5, .my-lg-n5 {
                margin-top: -3rem !important
            }

            .mr-lg-n5, .mx-lg-n5 {
                margin-right: -3rem !important
            }

            .mb-lg-n5, .my-lg-n5 {
                margin-bottom: -3rem !important
            }

            .ml-lg-n5, .mx-lg-n5 {
                margin-left: -3rem !important
            }

            .m-lg-auto {
                margin: auto !important
            }

            .mt-lg-auto, .my-lg-auto {
                margin-top: auto !important
            }

            .mr-lg-auto, .mx-lg-auto {
                margin-right: auto !important
            }

            .mb-lg-auto, .my-lg-auto {
                margin-bottom: auto !important
            }

            .ml-lg-auto, .mx-lg-auto {
                margin-left: auto !important
            }
        }

        .text-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace
        }

        .text-justify {
            text-align: justify !important
        }

        .text-wrap {
            white-space: normal !important
        }

        .text-nowrap {
            white-space: nowrap !important
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .text-left {
            text-align: left !important
        }

        .text-right {
            text-align: right !important
        }

        .text-center {
            text-align: center !important
        }

        @media (min-width: 576px) {
            .text-sm-left {
                text-align: left !important
            }

            .text-sm-right {
                text-align: right !important
            }

            .text-sm-center {
                text-align: center !important
            }
        }

        @media (min-width: 768px) {
            .text-md-left {
                text-align: left !important
            }

            .text-md-right {
                text-align: right !important
            }

            .text-md-center {
                text-align: center !important
            }
        }

        @media (min-width: 992px) {
            .text-lg-left {
                text-align: left !important
            }

            .text-lg-right {
                text-align: right !important
            }

            .text-lg-center {
                text-align: center !important
            }
        }

        @media (min-width: 1200px) {
            .float-xl-left {
                float: left !important
            }

            .float-xl-right {
                float: right !important
            }

            .float-xl-none {
                float: none !important
            }

            .m-xl-0 {
                margin: 0 !important
            }

            .mt-xl-0, .my-xl-0 {
                margin-top: 0 !important
            }

            .mr-xl-0, .mx-xl-0 {
                margin-right: 0 !important
            }

            .mb-xl-0, .my-xl-0 {
                margin-bottom: 0 !important
            }

            .ml-xl-0, .mx-xl-0 {
                margin-left: 0 !important
            }

            .m-xl-1 {
                margin: .25rem !important
            }

            .mt-xl-1, .my-xl-1 {
                margin-top: .25rem !important
            }

            .mr-xl-1, .mx-xl-1 {
                margin-right: .25rem !important
            }

            .mb-xl-1, .my-xl-1 {
                margin-bottom: .25rem !important
            }

            .ml-xl-1, .mx-xl-1 {
                margin-left: .25rem !important
            }

            .m-xl-2 {
                margin: .5rem !important
            }

            .mt-xl-2, .my-xl-2 {
                margin-top: .5rem !important
            }

            .mr-xl-2, .mx-xl-2 {
                margin-right: .5rem !important
            }

            .mb-xl-2, .my-xl-2 {
                margin-bottom: .5rem !important
            }

            .ml-xl-2, .mx-xl-2 {
                margin-left: .5rem !important
            }

            .m-xl-3 {
                margin: 1rem !important
            }

            .mt-xl-3, .my-xl-3 {
                margin-top: 1rem !important
            }

            .mr-xl-3, .mx-xl-3 {
                margin-right: 1rem !important
            }

            .mb-xl-3, .my-xl-3 {
                margin-bottom: 1rem !important
            }

            .ml-xl-3, .mx-xl-3 {
                margin-left: 1rem !important
            }

            .m-xl-4 {
                margin: 1.5rem !important
            }

            .mt-xl-4, .my-xl-4 {
                margin-top: 1.5rem !important
            }

            .mr-xl-4, .mx-xl-4 {
                margin-right: 1.5rem !important
            }

            .mb-xl-4, .my-xl-4 {
                margin-bottom: 1.5rem !important
            }

            .ml-xl-4, .mx-xl-4 {
                margin-left: 1.5rem !important
            }

            .m-xl-5 {
                margin: 3rem !important
            }

            .mt-xl-5, .my-xl-5 {
                margin-top: 3rem !important
            }

            .mr-xl-5, .mx-xl-5 {
                margin-right: 3rem !important
            }

            .mb-xl-5, .my-xl-5 {
                margin-bottom: 3rem !important
            }

            .ml-xl-5, .mx-xl-5 {
                margin-left: 3rem !important
            }

            .p-xl-0 {
                padding: 0 !important
            }

            .pt-xl-0, .py-xl-0 {
                padding-top: 0 !important
            }

            .pr-xl-0, .px-xl-0 {
                padding-right: 0 !important
            }

            .pb-xl-0, .py-xl-0 {
                padding-bottom: 0 !important
            }

            .pl-xl-0, .px-xl-0 {
                padding-left: 0 !important
            }

            .p-xl-1 {
                padding: .25rem !important
            }

            .pt-xl-1, .py-xl-1 {
                padding-top: .25rem !important
            }

            .pr-xl-1, .px-xl-1 {
                padding-right: .25rem !important
            }

            .pb-xl-1, .py-xl-1 {
                padding-bottom: .25rem !important
            }

            .pl-xl-1, .px-xl-1 {
                padding-left: .25rem !important
            }

            .p-xl-2 {
                padding: .5rem !important
            }

            .pt-xl-2, .py-xl-2 {
                padding-top: .5rem !important
            }

            .pr-xl-2, .px-xl-2 {
                padding-right: .5rem !important
            }

            .pb-xl-2, .py-xl-2 {
                padding-bottom: .5rem !important
            }

            .pl-xl-2, .px-xl-2 {
                padding-left: .5rem !important
            }

            .p-xl-3 {
                padding: 1rem !important
            }

            .pt-xl-3, .py-xl-3 {
                padding-top: 1rem !important
            }

            .pr-xl-3, .px-xl-3 {
                padding-right: 1rem !important
            }

            .pb-xl-3, .py-xl-3 {
                padding-bottom: 1rem !important
            }

            .pl-xl-3, .px-xl-3 {
                padding-left: 1rem !important
            }

            .p-xl-4 {
                padding: 1.5rem !important
            }

            .pt-xl-4, .py-xl-4 {
                padding-top: 1.5rem !important
            }

            .pr-xl-4, .px-xl-4 {
                padding-right: 1.5rem !important
            }

            .pb-xl-4, .py-xl-4 {
                padding-bottom: 1.5rem !important
            }

            .pl-xl-4, .px-xl-4 {
                padding-left: 1.5rem !important
            }

            .p-xl-5 {
                padding: 3rem !important
            }

            .pt-xl-5, .py-xl-5 {
                padding-top: 3rem !important
            }

            .pr-xl-5, .px-xl-5 {
                padding-right: 3rem !important
            }

            .pb-xl-5, .py-xl-5 {
                padding-bottom: 3rem !important
            }

            .pl-xl-5, .px-xl-5 {
                padding-left: 3rem !important
            }

            .m-xl-n1 {
                margin: -.25rem !important
            }

            .mt-xl-n1, .my-xl-n1 {
                margin-top: -.25rem !important
            }

            .mr-xl-n1, .mx-xl-n1 {
                margin-right: -.25rem !important
            }

            .mb-xl-n1, .my-xl-n1 {
                margin-bottom: -.25rem !important
            }

            .ml-xl-n1, .mx-xl-n1 {
                margin-left: -.25rem !important
            }

            .m-xl-n2 {
                margin: -.5rem !important
            }

            .mt-xl-n2, .my-xl-n2 {
                margin-top: -.5rem !important
            }

            .mr-xl-n2, .mx-xl-n2 {
                margin-right: -.5rem !important
            }

            .mb-xl-n2, .my-xl-n2 {
                margin-bottom: -.5rem !important
            }

            .ml-xl-n2, .mx-xl-n2 {
                margin-left: -.5rem !important
            }

            .m-xl-n3 {
                margin: -1rem !important
            }

            .mt-xl-n3, .my-xl-n3 {
                margin-top: -1rem !important
            }

            .mr-xl-n3, .mx-xl-n3 {
                margin-right: -1rem !important
            }

            .mb-xl-n3, .my-xl-n3 {
                margin-bottom: -1rem !important
            }

            .ml-xl-n3, .mx-xl-n3 {
                margin-left: -1rem !important
            }

            .m-xl-n4 {
                margin: -1.5rem !important
            }

            .mt-xl-n4, .my-xl-n4 {
                margin-top: -1.5rem !important
            }

            .mr-xl-n4, .mx-xl-n4 {
                margin-right: -1.5rem !important
            }

            .mb-xl-n4, .my-xl-n4 {
                margin-bottom: -1.5rem !important
            }

            .ml-xl-n4, .mx-xl-n4 {
                margin-left: -1.5rem !important
            }

            .m-xl-n5 {
                margin: -3rem !important
            }

            .mt-xl-n5, .my-xl-n5 {
                margin-top: -3rem !important
            }

            .mr-xl-n5, .mx-xl-n5 {
                margin-right: -3rem !important
            }

            .mb-xl-n5, .my-xl-n5 {
                margin-bottom: -3rem !important
            }

            .ml-xl-n5, .mx-xl-n5 {
                margin-left: -3rem !important
            }

            .m-xl-auto {
                margin: auto !important
            }

            .mt-xl-auto, .my-xl-auto {
                margin-top: auto !important
            }

            .mr-xl-auto, .mx-xl-auto {
                margin-right: auto !important
            }

            .mb-xl-auto, .my-xl-auto {
                margin-bottom: auto !important
            }

            .ml-xl-auto, .mx-xl-auto {
                margin-left: auto !important
            }

            .text-xl-left {
                text-align: left !important
            }

            .text-xl-right {
                text-align: right !important
            }

            .text-xl-center {
                text-align: center !important
            }
        }

        .btn--icon, .form-group--centered, .form-group--centered .form-control {
            text-align: center
        }

        .text-lowercase {
            text-transform: lowercase !important
        }

        .text-uppercase {
            text-transform: uppercase !important
        }

        .text-capitalize {
            text-transform: capitalize !important
        }

        .alert--notify__close, .avatar-char, .card-link, .checkbox__char, .content__title > h1, .modal-footer > .btn-link, .nav-tabs .nav-link, .top-menu > li > a, .view-more {
            text-transform: uppercase
        }

        .font-weight-light {
            font-weight: 300 !important
        }

        .font-weight-lighter {
            font-weight: lighter !important
        }

        .font-weight-normal {
            font-weight: 400 !important
        }

        .font-weight-bold {
            font-weight: 500 !important
        }

        .font-weight-bolder {
            font-weight: bolder !important
        }

        .font-italic {
            font-style: italic !important
        }

        .text-primary {
            color: #2196F3 !important
        }

        a.text-primary:focus, a.text-primary:hover {
            color: #0a6ebd !important
        }

        .text-secondary {
            color: #868e96 !important
        }

        a.text-secondary:focus, a.text-secondary:hover {
            color: #60686f !important
        }

        .text-success {
            color: #32c787 !important
        }

        a.text-success:focus, a.text-success:hover {
            color: #238a5e !important
        }

        .text-info {
            color: #03A9F4 !important
        }

        a.text-info:focus, a.text-info:hover {
            color: #0275a8 !important
        }

        .text-warning {
            color: #ffc721 !important
        }

        a.text-warning:focus, a.text-warning:hover {
            color: #d49e00 !important
        }

        .text-danger {
            color: #ff6b68 !important
        }

        a.text-danger:focus, a.text-danger:hover {
            color: #ff201c !important
        }

        .text-light {
            color: #f6f6f6 !important
        }

        a.text-light:focus, a.text-light:hover {
            color: #d0d0d0 !important
        }

        .text-dark {
            color: #495057 !important
        }

        a.text-dark:focus, a.text-dark:hover {
            color: #262a2d !important
        }

        .text-body {
            color: #747a80 !important
        }

        .text-muted {
            color: #9c9c9c !important
        }

        .text-black-50 {
            color: rgba(0, 0, 0, .5) !important
        }

        .text-white-50 {
            color: rgba(255, 255, 255, .5) !important
        }

        .text-hide {
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0
        }

        .breadcrumb-item + .breadcrumb-item:before, .btn-group--colors > .btn:before, .carousel-control-next-icon:after, .carousel-control-next-icon:before, .carousel-control-prev-icon:after, .carousel-control-prev-icon:before, .checkbox__char:after, .checkbox__char:before, .checkbox__label:after, .data-table table th > i.fa:before, .data-table__filter:before {
            font-family: Material-Design-Iconic-Font
        }

        .text-decoration-none {
            text-decoration: none !important
        }

        .text-reset {
            color: inherit !important
        }

        .visible {
            visibility: visible !important
        }

        .invisible {
            visibility: hidden !important
        }

        @media print {
            blockquote, img, pre, tr {
                page-break-inside: avoid
            }

            *, ::after, ::before {
                text-shadow: none !important;
                box-shadow: none !important
            }

            a:not(.btn) {
                text-decoration: underline
            }

            abbr[title]::after {
                content: " (" attr(title) ")"
            }

            pre {
                white-space: pre-wrap !important
            }

            blockquote, pre {
                border: 1px solid #adb5bd
            }

            thead {
                display: table-header-group
            }

            h2, h3, p {
                orphans: 3;
                widows: 3
            }

            h2, h3 {
                page-break-after: avoid
            }

            @page {
                size: a3
            }

            .container, body {
                min-width: 992px !important
            }

            .navbar {
                display: none
            }

            .badge {
                border: 1px solid #000
            }

            .table {
                border-collapse: collapse !important
            }

            .table td, .table th {
                background-color: #FFF !important
            }

            .table-bordered td, .table-bordered th {
                border: 1px solid #dee2e6 !important
            }

            .table-dark {
                color: inherit
            }

            .table-dark tbody + tbody, .table-dark td, .table-dark th, .table-dark thead th {
                border-color: #f2f4f5
            }

            .table .thead-dark th {
                color: inherit;
                border-color: #f2f4f5
            }
        }

        .dropdown, .dropup {
            position: relative
        }

        .dropdown-item {
            line-height: 1.5;
            transition: background-color .3s, color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .dropdown-item {
                transition: none
            }
        }

        .dropdown-menu {
            -webkit-animation-name: fadeIn;
            animation-name: fadeIn;
            animation-duration: .3s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .11)
        }

        .dropdown-header {
            font-size: 1rem;
            font-weight: 400
        }

        .dropdown-menu--block {
            width: 320px
        }

        @media (max-width: 575.98px) {
            .dropdown-menu--block {
                width: 100%
            }
        }

        .dropdown-menu--icon .dropdown-item > i {
            line-height: 100%;
            vertical-align: top;
            font-size: 1.4rem;
            width: 2rem
        }

        .dropdown-menu--sort > .checkbox {
            white-space: nowrap;
            padding: .5rem 1.5rem .25rem
        }

        .card {
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            margin-bottom: 2.3rem
        }

        .card:not([class*=border-]) {
            border: 0
        }

        .card--inverse, .card--inverse .card-title {
            color: #FFF
        }

        .card--inverse .card-subtitle {
            color: rgba(255, 255, 255, .75)
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 400;
            line-height: 140%;
            margin-top: -.35rem
        }

        .card-title:last-child {
            margin-bottom: 0
        }

        .card-subtitle {
            color: #9c9c9c;
            font-size: 1rem;
            font-weight: 400;
            margin-top: -1.5rem;
            line-height: 1.5
        }

        .card-subtitle:not(:last-child) {
            margin-bottom: 2rem
        }

        .card-body > .actions, .card > .actions {
            position: absolute;
            right: 15px;
            z-index: 2;
            top: 18px
        }

        [class*=card-img] {
            width: 100%
        }

        .card-link {
            font-size: .98rem;
            color: #333;
            display: inline-block;
            margin-top: 1rem;
            transition: color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .card-link {
                transition: none
            }
        }

        .card-link:hover {
            color: #666
        }

        .card-body .card-body {
            margin-bottom: 0
        }

        .card-body + .listview {
            margin-top: -.9rem
        }

        .card-body__title {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: 400
        }

        .checkbox__char, .table th {
            font-weight: 500
        }

        .card--fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 19;
            padding-top: 72px;
            overflow: auto
        }

        .card-footer, .card-header {
            padding-top: 1.3rem;
            padding-bottom: 1.3rem;
            background-color: #f9f9f9
        }

        .btn--action, .btn--icon {
            padding: 0;
            border-radius: 50%
        }

        .card-header {
            margin-bottom: -.35rem
        }

        .card-footer {
            margin-top: -.35rem
        }

        .btn {
            transition: box-shadow .3s, background-color .3s, border-color .3s
        }

        .btn:not([class*=btn-outline-]) {
            border-color: transparent !important
        }

        .btn--raised {
            box-shadow: 0 4px 3px -2px rgba(0, 0, 0, .15), 0 2px 2px 0 rgba(0, 0, 0, .04), 0 1px 5px 0 rgba(0, 0, 0, .02) !important
        }

        .btn--raised:hover {
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, .15), 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12) !important
        }

        .btn--raised:active {
            box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .2), 0 8px 10px 1px rgba(0, 0, 0, .14), 0 3px 10px 2px rgba(0, 0, 0, .12) !important
        }

        .btn--icon {
            width: 3rem;
            height: 3rem;
            font-size: 1.2rem
        }

        .btn--icon-text > .zmdi {
            font-size: 1.15rem;
            margin: -1px 5px 0 0;
            vertical-align: middle
        }

        .btn--action {
            z-index: 2;
            height: 50px;
            width: 50px;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 3px -2px rgba(0, 0, 0, .15), 0 2px 2px 0 rgba(0, 0, 0, .04), 0 1px 5px 0 rgba(0, 0, 0, .02) !important;
            position: fixed;
            bottom: 30px;
            right: 30px
        }

        .btn--action:hover {
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, .15), 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12) !important
        }

        .btn--action:active {
            box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .2), 0 8px 10px 1px rgba(0, 0, 0, .14), 0 3px 10px 2px rgba(0, 0, 0, .12) !important
        }

        .btn-group--colors > .btn, .form-control.is-invalid:focus, .form-control.is-valid:focus, .page-link:focus {
            box-shadow: none
        }

        .btn--action, .btn--action:focus, .btn--action:hover {
            color: #FFF
        }

        .btn-group-justified {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%
        }

        .btn-group-justified .btn, .btn-group-justified .btn-group {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1
        }

        .btn-group-justified .btn .btn, .btn-group-justified .btn-group .btn {
            width: 100%
        }

        [data-toggle=buttons]:not(.btn-group--colors) > .btn {
            background-color: #f6f6f6;
            cursor: pointer;
            box-shadow: none;
            border: 0;
            margin: 0
        }

        [data-toggle=buttons]:not(.btn-group--colors) > .btn:not(.active) {
            color: #747a80
        }

        [data-toggle=buttons]:not(.btn-group--colors) > .btn.active {
            background-color: #03A9F4;
            color: #FFF
        }

        .btn-group--colors > .btn {
            border-radius: 50% !important;
            width: 30px;
            height: 30px;
            margin-right: 5px;
            margin-bottom: 3px;
            position: relative
        }

        .btn-group--colors > .btn:not([class*=bg-]) {
            border-color: #f6f6f6 !important
        }

        .btn-group--colors > .btn:before {
            content: "";
            font-size: 16px;
            transition: opacity .2s, -webkit-transform .2s;
            transition: transform .2s, opacity .2s;
            transition: transform .2s, opacity .2s, -webkit-transform .2s;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            line-height: 28px;
            padding-right: 3px;
            color: #FFF;
            font-style: italic;
            -webkit-transform: scale(0);
            transform: scale(0);
            opacity: 0
        }

        .avatar-char, .checkbox__char, .lg-slide em {
            font-style: normal
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .btn-group--colors > .btn:before {
                transition: none
            }
        }

        .btn-group--colors > .btn.btn:not([class*=bg-]) {
            border: 1px solid #dde2e6
        }

        .btn-group--colors > .btn.btn:not([class*=bg-]):before {
            color: #747a80
        }

        .btn-group--colors > .btn.active:before {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 1
        }

        .table thead th {
            border-bottom-width: 1px
        }

        .table:not(.table-dark) thead:not(.thead-dark) {
            color: #333
        }

        .table tr[class*=table-] td, .table tr[class*=table-] th, .table tr[class*=table-] + tr td, .table tr[class*=table-] + tr th {
            border: 0
        }

        .table:not(.table-bordered) > tbody:first-child td, .table:not(.table-bordered) > tbody:first-child th, .table:not(.table-bordered) > thead:first-child td, .table:not(.table-bordered) > thead:first-child th {
            border-top: 0
        }

        .data-table table th {
            -webkit-user-select: none;
            user-select: none;
            cursor: pointer;
            position: relative
        }

        .data-table table th > i.fa {
            position: absolute;
            font-style: normal;
            right: .3rem;
            bottom: .6rem;
            font-size: 1.4rem
        }

        .data-table table th > i.fa.fa-chevron-up:before {
            content: '\f1ce'
        }

        .data-table table th > i.fa.fa-chevron-down:before {
            content: '\f1cd'
        }

        .data-table tr > td:first-child, .data-table tr > th:first-child {
            padding-left: 2.2rem
        }

        .data-table tr > td:last-child, .data-table tr > th:last-child {
            padding-right: 2.2rem
        }

        .data-table__header {
            padding: 0 2.2rem 2rem
        }

        .data-table__filter {
            max-width: 500px
        }

        .data-table__filter .form-control {
            padding-left: 2rem
        }

        .data-table__filter:before {
            content: '\f1c3';
            font-size: 1.5rem;
            position: absolute;
            left: 0;
            bottom: .263rem
        }

        .data-table__footer {
            text-align: center;
            padding: 2.1rem 2.2rem
        }

        .form-control {
            border-left: 0;
            border-right: 0;
            border-top: 0;
            resize: none;
            appearance: none;
            -ms-overflow-style: none
        }

        .form-control:not(:disabled):not([readonly]):focus ~ .form-group__bar:after, .form-control:not(:disabled):not([readonly]):focus ~ .form-group__bar:before {
            width: 50%
        }

        .form-control:disabled, .form-control[readonly] {
            opacity: .6
        }

        .form-group {
            position: relative
        }

        .form-group__bar {
            position: absolute;
            left: 0;
            bottom: 0;
            z-index: 3;
            width: 100%
        }

        .form-group__bar:after, .form-group__bar:before {
            content: '';
            position: absolute;
            height: 2px;
            width: 0;
            bottom: 0;
            transition: all 350ms;
            transition-timing-function: ease;
            background-color: #03A9F4
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .form-group__bar:after, .form-group__bar:before {
                transition: none
            }
        }

        .form-group__bar:before {
            left: 50%
        }

        .form-group__bar:after {
            right: 50%
        }

        select::-ms-expand {
            display: none
        }

        .select:before {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: 1;
            right: 0;
            bottom: 5px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 8px 8px;
            border-color: transparent transparent #d1d1d1
        }

        select.form-control {
            padding-bottom: 0;
            padding-top: 0
        }

        .form-group--float {
            margin-top: 2.5rem
        }

        .form-group--float .form-control.form-control--active ~ label, .form-group--float .form-control:focus ~ label {
            bottom: 2.25rem;
            font-size: .875rem
        }

        .form-group--float .form-control ~ label {
            font-size: 1rem;
            bottom: .375rem;
            width: 100%
        }

        .form-group--float .form-control:focus ~ label {
            color: #03A9F4
        }

        .form-group--float .form-control-sm.form-control--active ~ label, .form-group--float .form-control-sm:focus ~ label {
            bottom: 1.75rem;
            font-size: .775rem
        }

        .form-group--float .form-control-sm ~ label {
            font-size: .875rem;
            bottom: .5rem
        }

        .form-group--float .form-control-lg.form-control--active ~ label, .form-group--float .form-control-lg:focus ~ label {
            bottom: 2.65rem;
            font-size: 1rem
        }

        .form-group--float .form-control-lg ~ label {
            font-size: 1.25rem;
            bottom: .5rem
        }

        .form-group--float > label {
            color: #868e96;
            pointer-events: none;
            left: 0;
            position: absolute;
            margin: 0;
            transition: bottom .2s ease-out, color .2s ease-out, font-size .2s ease-out, color .3s
        }

        .invalid-feedback, .valid-feedback {
            position: absolute;
            left: 0;
            bottom: -1.5rem
        }

        .is-valid ~ .form-group__bar:after, .is-valid ~ .form-group__bar:before {
            background-color: #32c787
        }

        .is-invalid ~ .form-group__bar:after, .is-invalid ~ .form-group__bar:before {
            background-color: #ff6b68
        }

        .invalid-tooltip, .valid-tooltip {
            margin-top: 0;
            border-radius: 0;
            padding: .25rem .5rem .35rem
        }

        .icon-toggle {
            position: relative;
            width: 2.5rem;
            height: 2.5rem;
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .icon-toggle .zmdi {
            z-index: 2;
            font-size: 1.5rem;
            color: #ced4da;
            transition: color .3s
        }

        .icon-toggle input[type=checkbox] {
            position: absolute;
            z-index: 3;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            cursor: pointer;
            opacity: 0
        }

        .icon-toggle input[type=checkbox]:checked ~ .zmdi {
            color: #39bbb0
        }

        .icon-toggle:hover .zmdi {
            color: #adb5bd
        }

        .icon-toggle--red input[type=checkbox]:checked ~ .zmdi {
            color: #ff6b68
        }

        .icon-toggle--blue input[type=checkbox]:checked ~ .zmdi {
            color: #2196F3
        }

        .icon-toggle--green input[type=checkbox]:checked ~ .zmdi {
            color: #32c787
        }

        .icon-toggle--amber input[type=checkbox]:checked ~ .zmdi {
            color: #ffc721
        }

        .icon-toggle--blue-grey input[type=checkbox]:checked ~ .zmdi {
            color: #607D8B
        }

        .input-group {
            margin-bottom: 2rem
        }

        .input-group .form-control {
            padding-right: 1rem;
            padding-left: 1rem
        }

        .input-group-text {
            padding-right: 1rem !important;
            padding-left: 1rem !important;
            line-height: 100%
        }

        .input-group-text > .zmdi {
            position: relative;
            top: 1px
        }

        .checkbox, .radio {
            position: relative;
            line-height: 1.5rem
        }

        .checkbox + .checkbox, .checkbox + .radio, .radio + .checkbox, .radio + .radio {
            margin-top: .5rem
        }

        .checkbox:not(.checkbox--inline):not( .radio--inline):not(.checkbox--inline):not( .radio--inline), .radio:not(.checkbox--inline):not( .radio--inline):not(.checkbox--inline):not( .radio--inline) {
            display: block
        }

        .checkbox--inline, .radio--inline {
            display: inline-block
        }

        .checkbox--inline:not(:last-child), .radio--inline:not(:last-child) {
            margin-right: 2rem
        }

        .checkbox > input[type=checkbox], .checkbox > input[type=radio], .radio > input[type=checkbox], .radio > input[type=radio] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            pointer-events: none
        }

        .checkbox > input[type=checkbox]:checked ~ .checkbox__label:before, .checkbox > input[type=radio]:checked ~ .checkbox__label:before, .radio > input[type=checkbox]:checked ~ .checkbox__label:before, .radio > input[type=radio]:checked ~ .checkbox__label:before {
            background-color: #39bbb0
        }

        .checkbox > input[type=checkbox]:checked ~ .checkbox__label:before, .checkbox > input[type=checkbox]:checked ~ .radio__label:before, .checkbox > input[type=radio]:checked ~ .checkbox__label:before, .checkbox > input[type=radio]:checked ~ .radio__label:before, .radio > input[type=checkbox]:checked ~ .checkbox__label:before, .radio > input[type=checkbox]:checked ~ .radio__label:before, .radio > input[type=radio]:checked ~ .checkbox__label:before, .radio > input[type=radio]:checked ~ .radio__label:before {
            border-color: #39bbb0
        }

        .checkbox > input[type=checkbox]:checked ~ .checkbox__label:after, .checkbox > input[type=checkbox]:checked ~ .radio__label:after, .checkbox > input[type=radio]:checked ~ .checkbox__label:after, .checkbox > input[type=radio]:checked ~ .radio__label:after, .radio > input[type=checkbox]:checked ~ .checkbox__label:after, .radio > input[type=checkbox]:checked ~ .radio__label:after, .radio > input[type=radio]:checked ~ .checkbox__label:after, .radio > input[type=radio]:checked ~ .radio__label:after {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 1
        }

        .checkbox > input[type=checkbox]:disabled ~ .checkbox__label, .checkbox > input[type=checkbox]:disabled ~ .radio__label, .checkbox > input[type=radio]:disabled ~ .checkbox__label, .checkbox > input[type=radio]:disabled ~ .radio__label, .radio > input[type=checkbox]:disabled ~ .checkbox__label, .radio > input[type=checkbox]:disabled ~ .radio__label, .radio > input[type=radio]:disabled ~ .checkbox__label, .radio > input[type=radio]:disabled ~ .radio__label {
            opacity: .5;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .checkbox__label, .radio__label {
            position: relative;
            min-width: 18px;
            min-height: 18px;
            padding-left: 27px;
            text-align: left;
            margin: 0
        }

        .actions__item, .app-shortcuts__item, .avatar-char, .checkbox__label:after, .contacts__item, .footer, .groups__item, .icon-list > li > i, .listview__header, .load-more, .navigation > li > a > i, .new-contact__header, .page-link, .profile__img__edit, .top-menu, .top-nav > li > a, .top-nav__notifications .listview:before, .view-more {
            text-align: center
        }

        .checkbox__label:after, .checkbox__label:before, .radio__label:after, .radio__label:before {
            width: 18px;
            height: 18px;
            position: absolute;
            left: 0;
            top: 0
        }

        .checkbox__label:before, .radio__label:before {
            content: '';
            border: 2px solid;
            background-color: transparent;
            transition: border-color .2s, background-color .2s
        }

        .checkbox__label:after, .radio__label:after {
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0);
            transition: opacity 150ms, -webkit-transform 150ms;
            transition: transform 150ms, opacity 150ms;
            transition: transform 150ms, opacity 150ms, -webkit-transform 150ms
        }

        .checkbox__label:before {
            border-radius: 2px
        }

        .checkbox__char, .radio__label:before {
            border-radius: 50%
        }

        .checkbox__label:after {
            content: "";
            font-size: 1.25rem;
            color: #FFF;
            line-height: 18px
        }

        .radio__label:after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #39bbb0;
            top: 5px;
            left: 5px
        }

        .checkbox--char > input[type=checkbox]:checked ~ .checkbox__char {
            font-size: 0;
            background-color: #adb5bd !important
        }

        .checkbox--char > input[type=checkbox]:checked ~ .checkbox__char:after {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
            opacity: 1
        }

        .checkbox--char > input[type=checkbox]:not(:checked) ~ .checkbox__char:hover {
            font-size: 0
        }

        .checkbox--char > input[type=checkbox]:not(:checked) ~ .checkbox__char:hover:before {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
            opacity: 1
        }

        .checkbox__char {
            position: relative;
            height: 40px;
            width: 40px;
            color: #FFF;
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 1.25rem;
            cursor: pointer;
            transition: font-size .2s ease, background-color .3s
        }

        .checkbox__char:after, .checkbox__char:before {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .3s ease, -webkit-transform .3s ease;
            transition: transform .3s ease, opacity .3s ease;
            transition: transform .3s ease, opacity .3s ease, -webkit-transform .3s ease;
            font-weight: 400
        }

        .checkbox__char:before {
            content: "";
            font-size: 1.35rem
        }

        .checkbox__char:after {
            content: "";
            font-size: 1.5rem;
            -webkit-transform: scale3d(0, 0, 0);
            transform: scale3d(0, 0, 0)
        }

        .page-item.disabled {
            opacity: .6
        }

        .page-link {
            border-radius: 50% !important;
            width: 30px;
            height: 30px;
            line-height: 30px;
            z-index: 1;
            cursor: pointer;
            transition: background-color .3s, color .3s;
            margin: 0 1px
        }

        .page-link > .zmdi {
            font-size: 1.5rem
        }

        .pagination-first .page-link, .pagination-last .page-link, .pagination-next .page-link, .pagination-prev .page-link {
            font-size: 0
        }

        .pagination-first .page-link:before, .pagination-last .page-link:before, .pagination-next .page-link:before, .pagination-prev .page-link:before {
            font-family: Material-Design-Iconic-Font;
            font-size: 1rem
        }

        .pagination-prev .page-link:before {
            content: '\f2ff'
        }

        .pagination-next .page-link:before {
            content: '\f301'
        }

        .pagination-first .page-link:before, .pagination-last .page-link:before {
            content: '\f302'
        }

        .pagination-first .page-link:before {
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
            display: inline-block
        }

        .alert-danger .alert-link, .alert-info .alert-link, .alert-success .alert-link, .alert-warning .alert-link {
            color: #FFF
        }

        .alert-link {
            border-bottom: 1px solid rgba(255, 255, 255, .25)
        }

        .alert-dismissible .close {
            opacity: .5
        }

        .alert-dismissible .close:hover {
            color: #FFF;
            opacity: 1
        }

        .alert-heading {
            font-size: 1.1rem
        }

        .alert--notify {
            max-width: 600px;
            width: calc(100% - 30px);
            padding-right: 80px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, .15);
            color: rgba(255, 255, 255, .85)
        }

        .alert--notify:not(.alert-info):not(.alert-success):not(.alert-warning):not(.alert-danger) {
            background-color: rgba(0, 0, 0, .95)
        }

        .alert--notify:not(.alert-info):not(.alert-success):not(.alert-warning):not(.alert-danger) .alert--notify__close {
            color: #FFEB3B
        }

        .alert--notify:not(.alert-info):not(.alert-success):not(.alert-warning):not(.alert-danger) .alert--notify__close:hover {
            opacity: .8
        }

        .alert--notify__close {
            background-color: transparent;
            border: 0;
            padding: 0;
            cursor: pointer;
            font-weight: 500;
            position: absolute;
            right: 1.5rem;
            top: 1.1rem;
            color: #FFF
        }

        .accordion__content, .accordion__title {
            padding: .85rem 1.35rem
        }

        .close {
            transition: opacity .3s;
            cursor: pointer
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .close {
                transition: none
            }
        }

        .close, .close:hover {
            opacity: 1
        }

        .breadcrumb {
            border-bottom: 1px solid #e9ecef;
            border-radius: 0
        }

        .breadcrumb-item + .breadcrumb-item:before {
            position: relative;
            top: 1px;
            color: #9c9c9c
        }

        .breadcrumb-item > a, a.breadcrumb-item {
            color: #747a80
        }

        .breadcrumb-item > a:hover, a.breadcrumb-item:hover {
            color: #5c6165
        }

        .accordion__item {
            border: 1px solid #eee
        }

        .accordion__item + .accordion__item {
            margin-top: -1px
        }

        .accordion__title {
            display: block;
            cursor: pointer;
            color: #333;
            transition: background-color .5s
        }

        .accordion__title:hover {
            background-color: #f9f9f9
        }

        .carousel-item img {
            width: 100%;
            border-radius: 2px
        }

        .carousel-control-next-icon, .carousel-control-prev-icon {
            background-image: none;
            position: relative
        }

        .carousel-control-next-icon:after, .carousel-control-next-icon:before, .carousel-control-prev-icon:after, .carousel-control-prev-icon:before {
            font-size: 2rem;
            color: #FFF;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            text-shadow: 0 0 5px rgba(0, 0, 0, .6);
            transition: opacity 250ms linear, -webkit-transform 250ms linear;
            transition: opacity 250ms linear, transform 250ms linear;
            transition: opacity 250ms linear, transform 250ms linear, -webkit-transform 250ms linear
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .carousel-control-next-icon:after, .carousel-control-next-icon:before, .carousel-control-prev-icon:after, .carousel-control-prev-icon:before {
                transition: none
            }
        }

        .carousel-control-next-icon:after, .carousel-control-prev-icon:after {
            -webkit-transform: scale(5);
            transform: scale(5);
            opacity: 0
        }

        .carousel-control-next:hover .carousel-control-next-icon:after, .carousel-control-next:hover .carousel-control-prev-icon:after, .carousel-control-prev:hover .carousel-control-next-icon:after, .carousel-control-prev:hover .carousel-control-prev-icon:after {
            -webkit-transform: scale(1);
            transform: scale(1);
            opacity: 1
        }

        .carousel-control-next:hover .carousel-control-next-icon:before, .carousel-control-next:hover .carousel-control-prev-icon:before, .carousel-control-prev:hover .carousel-control-next-icon:before, .carousel-control-prev:hover .carousel-control-prev-icon:before {
            -webkit-transform: scale(0);
            transform: scale(0);
            opacity: 0
        }

        .carousel-control-prev-icon:after, .carousel-control-prev-icon:before {
            content: '\f2ff'
        }

        .carousel-control-next-icon:after, .carousel-control-next-icon:before {
            content: '\f301'
        }

        .carousel-caption {
            background-color: rgba(0, 0, 0, .5);
            border-radius: 2px 2px 0 0;
            bottom: 0;
            font-weight: 500;
            padding-bottom: 35px
        }

        .carousel-caption h3 {
            color: #FFF;
            font-size: 1.3rem
        }

        .modal-content {
            box-shadow: 0 5px 20px rgba(0, 0, 0, .07)
        }

        .modal-title {
            font-size: 1.2rem;
            font-weight: 400
        }

        .tooltip, b, strong {
            font-weight: 500
        }

        .modal-footer {
            padding-top: .8rem
        }

        .modal-footer > .btn-link {
            color: #333;
            font-weight: 500;
            border-radius: 2px
        }

        .modal-footer > .btn-link:focus, .modal-footer > .btn-link:hover {
            background-color: #f6f6f6
        }

        .popover {
            font-size: 1rem;
            box-shadow: 0 2px 30px rgba(0, 0, 0, .1)
        }

        .header, .top-menu {
            box-shadow: 0 5px 5px -3px rgba(0, 0, 0, .15)
        }

        .popover-header {
            border: 0;
            margin-bottom: -2rem
        }

        .nav-tabs .nav-link {
            border: 0;
            color: #9c9c9c;
            position: relative;
            font-size: .95rem;
            transition: color .4s
        }

        .nav-tabs .nav-link:before {
            content: "";
            height: 2px;
            position: absolute;
            width: 100%;
            left: 0;
            bottom: 0;
            background-color: #03A9F4;
            -webkit-transform: scaleX(0);
            transform: scaleX(0);
            transition: -webkit-transform .4s;
            transition: transform .4s;
            transition: transform .4s, -webkit-transform .4s
        }

        .content__title, .main {
            position: relative
        }

        .nav-tabs .nav-link.active:before {
            -webkit-transform: scaleX(1);
            transform: scaleX(1)
        }

        .nav-tabs:not([class*=nav-tabs--]) .nav-link.active {
            color: #2196F3
        }

        .tab-content {
            padding: 1.5rem 0
        }

        .nav-tabs--red .nav-link.active {
            color: #ff6b68
        }

        .nav-tabs--red .nav-link:before {
            background-color: #ff6b68
        }

        .nav-tabs--green .nav-link.active {
            color: #32c787
        }

        .nav-tabs--green .nav-link:before {
            background-color: #32c787
        }

        .nav-tabs--amber .nav-link.active {
            color: #FF9800
        }

        .nav-tabs--amber .nav-link:before {
            background-color: #FF9800
        }

        .nav-tabs--black .nav-link.active {
            color: #000
        }

        .nav-tabs--black .nav-link:before {
            background-color: #000
        }

        .tooltip {
            font-size: .95rem
        }

        @font-face {
            font-family: Roboto;
            src: url(../fonts/roboto/Roboto-Light-webfont.eot);
            src: url(../fonts/roboto/Roboto-Light-webfont.eot?#iefix) format("embedded-opentype"), url(../fonts/roboto/Roboto-Light-webfont.woff) format("woff"), url(../fonts/roboto/Roboto-Light-webfont.ttf) format("truetype"), url(../fonts/roboto/Roboto-Light-webfont.svg#icon) format("svg");
            font-weight: 300;
            font-style: normal
        }

        @font-face {
            font-family: Roboto;
            src: url(../fonts/roboto/Roboto-Regular-webfont.eot);
            src: url(../fonts/roboto/Roboto-Regular-webfont.eot?#iefix) format("embedded-opentype"), url(../fonts/roboto/Roboto-Regular-webfont.woff) format("woff"), url(../fonts/roboto/Roboto-Regular-webfont.ttf) format("truetype"), url(../fonts/roboto/Roboto-Regular-webfont.svg#icon) format("svg");
            font-weight: 400;
            font-style: normal
        }

        @font-face {
            font-family: Roboto;
            src: url(../fonts/roboto/Roboto-Medium-webfont.eot);
            src: url(../fonts/roboto/Roboto-Medium-webfont.eot?#iefix) format("embedded-opentype"), url(../fonts/roboto/Roboto-Medium-webfont.woff) format("woff"), url(../fonts/roboto/Roboto-Medium-webfont.ttf) format("truetype"), url(../fonts/roboto/Roboto-Medium-webfont.svg#icon) format("svg");
            font-weight: 500;
            font-style: normal
        }

        @font-face {
            font-family: Roboto;
            src: url(../fonts/roboto/Roboto-Bold-webfont.eot);
            src: url(../fonts/roboto/Roboto-Bold-webfont.eot?#iefix) format("embedded-opentype"), url(../fonts/roboto/Roboto-Bold-webfont.woff) format("woff"), url(../fonts/roboto/Roboto-Bold-webfont.ttf) format("truetype"), url(../fonts/roboto/Roboto-Bold-webfont.svg#icon) format("svg");
            font-weight: 700;
            font-style: normal
        }

        .bg-gray {
            background-color: #868e96 !important
        }

        .text-gray {
            color: #868e96 !important
        }

        .bg-gray-dark {
            background-color: #343a40 !important
        }

        .text-gray-dark {
            color: #343a40 !important
        }

        .bg-white {
            background-color: #FFF !important
        }

        .text-white {
            color: #FFF !important
        }

        .bg-black {
            background-color: #000 !important
        }

        .text-black {
            color: #000 !important
        }

        .bg-red {
            background-color: #ff6b68 !important
        }

        .text-red {
            color: #ff6b68 !important
        }

        .bg-pink {
            background-color: #ff85af !important
        }

        .text-pink {
            color: #ff85af !important
        }

        .bg-purple {
            background-color: #d066e2 !important
        }

        .text-purple {
            color: #d066e2 !important
        }

        .bg-deep-purple {
            background-color: #673AB7 !important
        }

        .text-deep-purple {
            color: #673AB7 !important
        }

        .bg-indigo {
            background-color: #3F51B5 !important
        }

        .text-indigo {
            color: #3F51B5 !important
        }

        .bg-blue {
            background-color: #2196F3 !important
        }

        .text-blue {
            color: #2196F3 !important
        }

        .bg-light-blue {
            background-color: #03A9F4 !important
        }

        .text-light-blue {
            color: #03A9F4 !important
        }

        .bg-cyan {
            background-color: #00BCD4 !important
        }

        .text-cyan {
            color: #00BCD4 !important
        }

        .bg-teal {
            background-color: #39bbb0 !important
        }

        .text-teal {
            color: #39bbb0 !important
        }

        .bg-green {
            background-color: #32c787 !important
        }

        .text-green {
            color: #32c787 !important
        }

        .bg-light-green {
            background-color: #8BC34A !important
        }

        .text-light-green {
            color: #8BC34A !important
        }

        .bg-lime {
            background-color: #CDDC39 !important
        }

        .text-lime {
            color: #CDDC39 !important
        }

        .bg-yellow {
            background-color: #FFEB3B !important
        }

        .text-yellow {
            color: #FFEB3B !important
        }

        .bg-amber {
            background-color: #ffc721 !important
        }

        .text-amber {
            color: #ffc721 !important
        }

        .bg-orange {
            background-color: #FF9800 !important
        }

        .text-orange {
            color: #FF9800 !important
        }

        .bg-deep-orange {
            background-color: #FF5722 !important
        }

        .text-deep-orange {
            color: #FF5722 !important
        }

        .bg-brown {
            background-color: #795548 !important
        }

        .text-brown {
            color: #795548 !important
        }

        .bg-blue-grey {
            background-color: #607D8B !important
        }

        .text-blue-grey {
            color: #607D8B !important
        }

        @media (max-width: 576px) {
            .hidden-lg-down, .hidden-md-down, .hidden-sm-down, .hidden-unless-lg, .hidden-unless-md, .hidden-unless-sm, .hidden-unless-xl, .hidden-xl-down, .hidden-xs-down, .hidden-xs-up {
                display: none !important
            }
        }

        @media (min-width: 576px) and (max-width: 767px) {
            .hidden-lg-down, .hidden-md-down, .hidden-sm-down, .hidden-sm-up, .hidden-unless-lg, .hidden-unless-md, .hidden-unless-xl, .hidden-unless-xs, .hidden-xl-down, .hidden-xs-up {
                display: none !important
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .hidden-lg-down, .hidden-md-down, .hidden-md-up, .hidden-sm-up, .hidden-unless-lg, .hidden-unless-sm, .hidden-unless-xl, .hidden-unless-xs, .hidden-xl-down, .hidden-xs-up {
                display: none !important
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            .hidden-lg-down, .hidden-lg-up, .hidden-md-up, .hidden-sm-up, .hidden-unless-md, .hidden-unless-sm, .hidden-unless-xl, .hidden-unless-xs, .hidden-xl-down, .hidden-xs-up {
                display: none !important
            }
        }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale
        }

        :active, :focus {
            outline: 0 !important
        }

        html {
            font-size: 13.5px
        }

        a {
            cursor: pointer
        }

        pre {
            background-color: #343a40;
            border-radius: 2px;
            padding: 1.5rem
        }

        button, input, optgroup, select, textarea {
            font-family: Roboto, sans-serif
        }

        .list {
            padding-left: 0
        }

        .list > li:before {
            font-family: Material-Design-Iconic-Font;
            margin-right: 1.1rem
        }

        .list--star > li:before {
            content: '\f27d'
        }

        .list--check > li:before {
            content: '\f26b'
        }

        .list--dot > li:before {
            content: '\f26f'
        }

        .main--alt {
            padding-top: 40px
        }

        @media (min-width: 1200px) {
            .hidden-lg-up, .hidden-md-up, .hidden-sm-up, .hidden-unless-lg, .hidden-unless-md, .hidden-unless-sm, .hidden-unless-xs, .hidden-xl-down, .hidden-xl-up, .hidden-xs-up {
                display: none !important
            }

            .content:not(.content--boxed):not(.content--full) {
                padding: 102px 30px 0 270px
            }

            .header__logo {
                min-width: calc(270px - 2rem)
            }
        }

        @media (min-width: 576px) and (max-width: 1199.98px) {
            .content:not(.content--boxed):not(.content--full) {
                padding: 102px 30px 0
            }
        }

        @media (max-width: 575.98px) {
            .content:not(.content--boxed):not(.content--full) {
                padding: 87px 15px 0
            }
        }

        @media (min-width: 576px) {
            .content--full {
                padding: 102px 30px 0
            }
        }

        @media (max-width: 767.98px) {
            .content--full {
                padding: 87px 15px 0
            }
        }

        .content__inner {
            margin: auto
        }

        .content__inner:not(.content__inner--sm) {
            max-width: 1280px
        }

        .content__inner--sm {
            max-width: 800px
        }

        .content__title {
            margin-bottom: 2rem;
            padding: 1.5rem 2rem 0
        }

        .content__title > h1 {
            line-height: 100%;
            font-weight: 400;
            font-size: 1.15rem;
            margin: 0;
            color: #676767
        }

        .content__title .actions {
            position: absolute;
            top: .3rem;
            right: 1rem
        }

        .content__title > small {
            font-size: 1rem;
            display: block;
            margin-top: .8rem;
            color: #959595
        }

        [data-columns]::after {
            display: block;
            clear: both;
            content: ""
        }

        @media (min-width: 1500px) {
            [data-columns]:before {
                content: '3 .column.size-1of3'
            }
        }

        @media (min-width: 768px) {
            [data-columns] {
                margin: 0 -15px
            }

            [data-columns] .column {
                padding: 0 15px
            }
        }

        @media (min-width: 768px) and (max-width: 1499px) {
            [data-columns]:before {
                content: '2 .column.size-1of2'
            }
        }

        @media screen and (max-width: 767px) {
            [data-columns] {
                margin: 0 -10px
            }

            [data-columns] .column {
                padding: 0 10px
            }

            [data-columns]:before {
                content: '1 .column.size-1of1'
            }
        }

        .column {
            float: left
        }

        .size-1of1 {
            width: 100%
        }

        .size-1of2 {
            width: 50%
        }

        .size-1of3 {
            width: 33.333%
        }

        .view-more {
            display: block;
            padding: 1.1rem 0;
            margin-top: .5rem;
            font-size: .9rem;
            font-weight: 500;
            transition: color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .view-more {
                transition: none
            }
        }

        .view-more:not(.view-more--light) {
            color: #9c9c9c
        }

        .view-more:not(.view-more--light):hover {
            color: #838282
        }

        .view-more--light {
            color: #FFF
        }

        .view-more--light:hover {
            color: rgba(255, 255, 255, .8)
        }

        .load-more {
            margin-top: 2rem
        }

        .load-more > a {
            display: inline-block;
            padding: .5rem 1rem;
            border: 2px solid rgba(0, 0, 0, .065);
            border-radius: 2px;
            color: #747a80;
            transition: border-color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .load-more > a {
                transition: none
            }
        }

        .load-more > a > i {
            font-size: 1.2rem;
            vertical-align: middle;
            margin: 0 .3rem 0 -.1rem;
            transition: -webkit-transform .3s;
            transition: transform .3s;
            transition: transform .3s, -webkit-transform .3s
        }

        .actions__item, .icon-list > li address {
            vertical-align: top;
            display: inline-block
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .load-more > a > i {
                transition: none
            }
        }

        .load-more > a:hover {
            border-color: rgba(0, 0, 0, .12)
        }

        .load-more > a:hover > i {
            -webkit-transform: rotate(-360deg);
            transform: rotate(-360deg)
        }

        .card-body .view-more {
            padding: 1rem 0 0
        }

        .actions:not(.actions--inverse) .actions__item {
            color: #a9adb1
        }

        .actions:not(.actions--inverse) .actions__item:hover {
            color: #747a80
        }

        .actions:not(.actions--inverse) .actions__item.actions__item--active {
            color: #5c6165
        }

        .actions__item {
            line-height: 31px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color .3s;
            margin: 0 2px;
            width: 30px;
            height: 30px
        }

        .avatar-char, .avatar-char > .zmdi {
            line-height: 3rem
        }

        .actions__item > i {
            display: inline-block;
            width: 100%
        }

        .actions--inverse .actions__item {
            color: rgba(255, 255, 255, .7)
        }

        .actions--inverse .actions__item--active, .actions--inverse .actions__item:hover {
            color: #FFF
        }

        .icon-list {
            padding: 0;
            margin: 0
        }

        .icon-list > li {
            position: relative;
            padding: .3rem 0
        }

        .icon-list > li > i {
            width: 2.5rem;
            font-size: 1.25rem;
            top: .12rem;
            position: relative;
            margin-left: -.5rem
        }

        .avatar-char, .avatar-img {
            border-radius: 50%;
            width: 3rem;
            height: 3rem
        }

        .header, .ma-backdrop {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0
        }

        .avatar-char {
            font-size: 1.2rem;
            color: #FFF
        }

        .ma-backdrop {
            height: 100%;
            cursor: pointer;
            z-index: 18
        }

        .tags > a {
            color: #747a80;
            border: 2px solid #dee2e6;
            border-radius: 2px;
            padding: .45rem .8rem;
            display: inline-block;
            margin: 0 .1rem .4rem;
            transition: color .3s, border-color .3s
        }

        .tags > a:hover {
            color: #5c6165;
            border-color: #c1c9d0
        }

        .header, .header__logo > h1 > a {
            color: #FFF
        }

        .header {
            height: 72px;
            padding: 0 2rem;
            z-index: 20;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .header::after {
            display: block;
            clear: both;
            content: ""
        }

        .header .ma-backdrop {
            position: absolute
        }

        .navigation-trigger {
            float: left;
            padding: 2rem 2rem 2rem 2.4rem;
            margin-left: -2rem;
            cursor: pointer
        }

        .navigation-trigger.toggled .navigation-trigger__inner {
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg)
        }

        .navigation-trigger.toggled .navigation-trigger__inner:before {
            -webkit-transform: scale(1);
            transform: scale(1)
        }

        .navigation-trigger.toggled .navigation-trigger__line:first-child {
            width: 12px;
            -webkit-transform: translateX(8px) translateY(1px) rotate(45deg);
            transform: translateX(8px) translateY(1px) rotate(45deg)
        }

        .navigation-trigger.toggled .navigation-trigger__line:last-child {
            width: 11px;
            -webkit-transform: translateX(8px) translateY(-1px) rotate(-45deg);
            transform: translateX(8px) translateY(-1px) rotate(-45deg)
        }

        .navigation-trigger__inner, .navigation-trigger__line {
            width: 18px;
            transition: all .3s
        }

        .navigation-trigger__inner {
            position: relative
        }

        .navigation-trigger__inner:before {
            content: '';
            position: absolute;
            width: 40px;
            height: 40px;
            left: -11px;
            top: -14px;
            background-color: rgba(255, 255, 255, .25);
            border-radius: 50%;
            transition: all .3s;
            -webkit-transform: scale(0);
            transform: scale(0)
        }

        .navigation-trigger__line {
            height: 2px;
            background-color: #FFF;
            display: block;
            position: relative
        }

        .navigation-trigger__line:not(:last-child) {
            margin-bottom: 3px
        }

        .header__logo > h1 {
            line-height: 100%;
            font-size: 1.3rem;
            font-weight: 400;
            margin: 0
        }

        .top-nav {
            margin: 0 0 0 auto;
            padding: 0
        }

        .top-nav > li {
            display: inline-block;
            vertical-align: middle
        }

        .top-nav > li > a {
            display: block;
            color: #FFF;
            border-radius: 2px;
            line-height: 100%;
            position: relative;
            transition: background-color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .top-nav > li > a {
                transition: none
            }
        }

        .top-nav > li > a:not(.header__nav__text) {
            padding: .5rem .15rem;
            min-width: 50px
        }

        .top-nav > li > a:not(.header__nav__text) > .zmdi {
            font-size: 1.65rem;
            line-height: 100%
        }

        .top-nav > li > a.active, .top-nav > li > a:hover {
            background-color: rgba(255, 255, 255, .2)
        }

        .top-nav > li .dropdown-menu--block {
            padding: 0
        }

        @media (max-width: 575.98px) {
            .top-nav > li {
                position: static
            }

            .top-nav > li .dropdown-menu--block {
                left: 20px;
                width: calc(100% - 40px);
                top: 62px
            }
        }

        .top-nav__notifications .listview {
            position: relative
        }

        .top-nav__notifications .listview:before {
            font-family: Material-Design-Iconic-Font;
            content: "";
            font-size: 2.5rem;
            transition: opacity .3s, -webkit-transform .3s;
            transition: transform .3s, opacity .3s;
            transition: transform .3s, opacity .3s, -webkit-transform .3s;
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            width: 90px;
            height: 90px;
            border: 2px solid #ececec;
            color: #8e9499;
            border-radius: 50%;
            -webkit-transform: scale(0) rotate(-360deg);
            transform: scale(0) rotate(-360deg);
            opacity: 0;
            line-height: 86px
        }

        .top-nav__notifications .listview__scroll {
            height: 350px
        }

        .top-nav__notifications--cleared .listview:before {
            -webkit-transform: scale(1) rotate(0);
            transform: scale(1) rotate(0);
            opacity: 1
        }

        .top-nav__notify:before {
            content: '';
            width: 7px;
            height: 7px;
            background-color: #ff7572;
            color: #FFF;
            border-radius: 50%;
            position: absolute;
            top: -3px;
            right: 0;
            left: 0;
            margin: auto;
            -webkit-animation-name: flash;
            animation-name: flash;
            -webkit-animation-duration: 2s;
            animation-duration: 2s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation-iteration-count: infinite;
            animation-iteration-count: infinite
        }

        .search {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            margin-right: 2.5rem;
            position: relative
        }

        @media (max-width: 1199.98px) {
            .search {
                padding: 0 1.5rem;
                position: absolute;
                left: 0;
                top: 0;
                height: 100%;
                width: 100%;
                background-color: #FFF;
                z-index: 21;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                transition: -webkit-transform .3s;
                transition: transform .3s;
                transition: transform .3s, -webkit-transform .3s
            }

            .search:not(.search--toggled) {
                -webkit-transform: translate3d(0, -105%, 0);
                transform: translate3d(0, -105%, 0)
            }

            .search__inner {
                max-width: 600px;
                margin: 0 auto;
                width: 100%
            }
        }

        .search__inner {
            position: relative
        }

        .search__text {
            border: 0;
            border-radius: 2px;
            height: 2.9rem;
            padding: 0 1rem 0 3rem;
            width: 100%;
            transition: background-color .3s, color .3s
        }

        @media (min-width: 992px) {
            .search__text {
                background-color: rgba(255, 255, 255, .2);
                color: #FFF
            }

            .search__text::-webkit-input-placeholder {
                color: #FFF;
                opacity: 1
            }

            .search__text:-ms-input-placeholder {
                color: #FFF;
                opacity: 1
            }

            .search__text::placeholder {
                color: #FFF;
                opacity: 1
            }

            .search__text:focus {
                background-color: #FFF;
                color: #495057
            }

            .search__text:focus::-webkit-input-placeholder {
                color: #606a73;
                opacity: 1
            }

            .search__text:focus:-ms-input-placeholder {
                color: #606a73;
                opacity: 1
            }

            .search__text:focus::placeholder {
                color: #606a73;
                opacity: 1
            }
        }

        .search__helper {
            position: absolute;
            left: 0;
            top: 0;
            font-size: 1.3rem;
            height: 100%;
            width: 3rem;
            text-align: center;
            line-height: 3rem;
            cursor: pointer;
            transition: color .3s, -webkit-transform .3s ease-out;
            transition: color .3s, transform .3s ease-out;
            transition: color .3s, transform .3s ease-out, -webkit-transform .3s ease-out
        }

        @media (max-width: 1199.98px) {
            .search__text {
                background-color: #f6f6f6;
                color: #495057
            }

            .search__text::-webkit-input-placeholder {
                color: #606a73;
                opacity: 1
            }

            .search__text:-ms-input-placeholder {
                color: #606a73;
                opacity: 1
            }

            .search__text::placeholder {
                color: #606a73;
                opacity: 1
            }

            .search__helper {
                color: #495057;
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
                line-height: 2.9rem
            }

            .search__helper:before {
                content: '\f301'
            }

            .search__helper:hover {
                opacity: .9
            }
        }

        .search--focus .search__helper {
            color: #606a73;
            -webkit-transform: rotate(180deg);
            transform: rotate(180deg);
            line-height: 2.9rem
        }

        .search--focus .search__helper:before {
            content: '\f301'
        }

        .footer__nav .nav-link + .nav-link:before, .navigation__sub .navigation__active:before {
            content: "";
            font-family: Material-Design-Iconic-Font
        }

        .app-shortcuts {
            margin: 0;
            padding: 1rem
        }

        .app-shortcuts__item {
            padding: 1rem 0;
            border-radius: 2px;
            position: relative;
            overflow: hidden;
            transition: background-color .3s
        }

        .app-shortcuts__item:hover > small {
            color: #FFF
        }

        .app-shortcuts__item:hover > i {
            background-color: rgba(255, 255, 255, .15)
        }

        .app-shortcuts__item:hover .app-shortcuts__helper {
            -webkit-transform: scale(3.5);
            transform: scale(3.5)
        }

        .app-shortcuts__item > i {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            color: #FFF;
            line-height: 45px;
            font-size: 1.5rem;
            transition: background-color .5s
        }

        .app-shortcuts__item > small {
            display: block;
            margin-top: .5rem;
            font-size: .95rem;
            color: #9c9c9c;
            transition: color .5s
        }

        .app-shortcuts__helper {
            position: absolute;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            top: 13px;
            left: 0;
            right: 0;
            margin: auto;
            z-index: -1;
            transition: -webkit-transform .5s;
            transition: transform .5s;
            transition: transform .5s, -webkit-transform .5s;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden
        }

        .user, .user__info {
            border-radius: 2px
        }

        .top-menu {
            position: absolute;
            left: 0;
            top: 100%;
            width: 100%;
            padding: 0 1rem .5rem
        }

        .top-menu > li {
            display: inline-block
        }

        .top-menu > li.active {
            position: relative;
            box-shadow: 0 0 0 -2px red
        }

        .top-menu > li > a {
            line-height: 100%;
            color: rgba(255, 255, 255, .65);
            font-weight: 500;
            padding: 1rem;
            display: block;
            transition: color .3s
        }

        .top-menu > li.active > a, .top-menu > li > a:hover {
            color: #FFF
        }

        .footer {
            padding: 4rem 1rem 1rem
        }

        .footer > p {
            color: #a9adb1;
            margin-bottom: 0
        }

        .footer__nav {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .footer__nav .nav-link {
            color: #a9adb1;
            transition: color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .footer__nav .nav-link {
                transition: none
            }
        }

        .footer__nav .nav-link:focus, .footer__nav .nav-link:hover {
            color: #81878d
        }

        .footer__nav .nav-link + .nav-link:before {
            font-size: 4px;
            position: relative;
            left: -1.2rem;
            top: -.2rem;
            color: #a9adb1
        }

        .sidebar {
            width: 340px;
            position: fixed;
            left: 0;
            padding: 102px 2rem .5rem;
            height: 100%;
            overflow: hidden;
            z-index: 19
        }

        .navigation > li > a, .navigation__sub .navigation__active, .user {
            position: relative
        }

        @media (max-width: 1199.98px) {
            .sidebar {
                background-color: #FFF;
                transition: opacity .3s, -webkit-transform .3s;
                transition: transform .3s, opacity .3s;
                transition: transform .3s, opacity .3s, -webkit-transform .3s
            }

            .sidebar:not(.toggled) {
                opacity: 0;
                -webkit-transform: translate3d(-100%, 0, 0);
                transform: translate3d(-100%, 0, 0)
            }

            .sidebar.toggled {
                box-shadow: 5px 0 10px rgba(0, 0, 0, .08);
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0)
            }
        }

        .sidebar .scrollbar-inner > .scroll-element {
            margin-right: 0
        }

        .sidebar--hidden {
            background-color: #FFF;
            transition: opacity .3s, -webkit-transform .3s;
            transition: transform .3s, opacity .3s;
            transition: transform .3s, opacity .3s, -webkit-transform .3s
        }

        .user, .user__info:hover {
            background-color: rgba(0, 0, 0, .04)
        }

        .sidebar--hidden:not(.toggled) {
            opacity: 0;
            -webkit-transform: translate3d(-100%, 0, 0);
            transform: translate3d(-100%, 0, 0)
        }

        .sidebar--hidden.toggled {
            box-shadow: 5px 0 10px rgba(0, 0, 0, .08);
            opacity: 1;
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0)
        }

        .user {
            margin: 0 0 1.5rem
        }

        .user .dropdown-menu {
            width: 100%;
            -webkit-transform: none !important;
            transform: none !important
        }

        .user__info {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            cursor: pointer;
            font-size: .9rem;
            padding: .8rem;
            transition: background-color .3s
        }

        .user__img {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            margin-right: .8rem
        }

        .user__name {
            color: #333;
            font-weight: 500
        }

        .user__email {
            color: #9c9c9c;
            margin-top: .1rem;
            line-height: 100%
        }

        .navigation {
            padding: 0
        }

        .navigation li a {
            color: #747a80;
            transition: background-color .3s, color .3s;
            font-weight: 500;
            display: block
        }

        .navigation li:not(.navigation__active):not(.navigation__sub--active) a:hover {
            background-color: rgba(0, 0, 0, .04);
            color: #5c6165
        }

        .navigation > li > a {
            padding: .85rem .5rem;
            border-radius: 2px
        }

        .navigation > li > a > i {
            vertical-align: top;
            font-size: 1.3rem;
            position: relative;
            top: .1rem;
            width: 1.5rem;
            margin-right: .6rem
        }

        .navigation__sub > ul {
            border-radius: 2px;
            overflow: hidden;
            padding: 0
        }

        .navigation__sub > ul > li > a {
            padding: .6rem 1rem .6rem 2.75rem
        }

        .navigation__sub > ul > li:last-child {
            padding-bottom: .8rem
        }

        .navigation__sub:not(.navigation__sub--active) > ul {
            display: none
        }

        .navigation__sub .navigation__active:before {
            font-size: 6px;
            position: absolute;
            left: 1rem;
            top: 1.1rem
        }

        .chat {
            position: fixed;
            top: 0;
            right: 0;
            width: 320px;
            height: 100%;
            background-color: #FFF;
            z-index: 21;
            box-shadow: -5px 0 10px rgba(0, 0, 0, .08);
            padding-top: 30px;
            transition: opacity .3s, -webkit-transform .3s;
            transition: transform .3s, opacity .3s;
            transition: transform .3s, opacity .3s, -webkit-transform .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .chat {
                transition: none
            }
        }

        .chat:not(.toggled) {
            -webkit-transform: translate3d(340px, 0, 0);
            transform: translate3d(340px, 0, 0);
            opacity: 0
        }

        .chat.toggled {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1
        }

        .chat__header {
            padding: 0 2rem
        }

        .chat__title {
            line-height: 100%;
            margin: 0 0 2rem;
            font-size: 1.2rem
        }

        .chat__title > small {
            color: #9c9c9c;
            font-size: .95rem;
            display: block;
            margin-top: .5rem;
            text-transform: none
        }

        .contacts__btn, .fc th, .flatpickr-current-month, .listview__header, .price-table__action, .price-table__title, .q-a__stat > span > small {
            text-transform: uppercase
        }

        .chat__search .form-group:before {
            font-family: Material-Design-Iconic-Font;
            content: "";
            font-size: 1.4rem;
            position: absolute;
            left: 0;
            bottom: .3rem
        }

        .chat__search .form-control {
            padding-left: 2rem
        }

        .chat__buddies {
            height: calc(100vh - 150px)
        }

        .chat__buddies .listview__item {
            padding-left: 3rem
        }

        .chat__available, .chat__away, .chat__busy {
            position: relative
        }

        .chat__available:before, .chat__away:before, .chat__busy:before {
            position: absolute;
            height: 8px;
            width: 8px;
            content: '';
            border-radius: 50%;
            left: 1.5rem;
            top: 0;
            bottom: 0;
            margin: auto
        }

        .chat__available:before {
            background-color: #32c787
        }

        .chat__away:before {
            background-color: #FF9800
        }

        .chat__busy:before {
            background-color: #ff6b68
        }

        .toggle-switch {
            display: inline-block;
            width: 36px;
            height: 20px;
            position: relative
        }

        .toggle-switch__helper {
            position: absolute;
            height: 12px;
            width: 100%
        }

        .toggle-switch__helper:after, .toggle-switch__helper:before {
            content: '';
            position: absolute;
            left: 0;
            transition: left .3s, background-color, .3s
        }

        .toggle-switch__helper:before {
            background-color: #e0e0e0;
            top: 4px;
            height: 100%;
            width: 100%;
            border-radius: 10px
        }

        .toggle-switch__helper:after {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #FFF;
            left: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .2);
            z-index: 1
        }

        .toggle-switch__checkbox {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            z-index: 2;
            cursor: pointer
        }

        .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            left: calc(100% - 20px);
            background-color: #39bbb0
        }

        .toggle-switch__checkbox:disabled ~ .toggle-switch__helper {
            opacity: .65
        }

        .toggle-switch__checkbox:active ~ .toggle-switch__helper:after {
            box-shadow: 0 0 0 10px rgba(0, 0, 0, .05)
        }

        .toggle-switch--red .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            background-color: #ff6b68
        }

        .toggle-switch--blue .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            background-color: #2196F3
        }

        .toggle-switch--green .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            background-color: #32c787
        }

        .toggle-switch--amber .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            background-color: #FF9800
        }

        .toggle-switch--purple .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            background-color: #d066e2
        }

        .toggle-switch--cyan .toggle-switch__checkbox:checked ~ .toggle-switch__helper:after {
            background-color: #00BCD4
        }

        .listview__header {
            color: #333;
            padding: 1.2rem 1rem 1rem;
            border-bottom: 1px solid #f6f6f6
        }

        .listview__header .actions {
            position: absolute;
            top: .8rem;
            right: 1rem
        }

        .listview__scroll {
            overflow-y: auto
        }

        .listview__item {
            padding: 1.25rem 2.2rem;
            transition: background-color .3s
        }

        .listview__item > .avatar-char {
            margin-right: 1.2rem
        }

        .listview:not(.listview--block) .listview__item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .listview__img {
            height: 3rem;
            border-radius: 50%;
            vertical-align: top;
            margin: 0 1.2rem 0 0
        }

        .listview__content {
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            min-width: 0
        }

        .listview__content > p {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #8e9499;
            margin-bottom: 0
        }

        .listview__heading {
            font-size: 1rem;
            color: #333;
            position: relative
        }

        .listview__heading > small {
            float: right;
            color: #9c9c9c;
            font-weight: 500;
            font-size: .85rem;
            margin-top: .1rem
        }

        .listview__heading + p {
            margin: .2rem 0 0;
            font-size: .95rem
        }

        .listview__attrs {
            -webkit-box-flex: 1;
            -ms-flex: 1 100%;
            flex: 1 100%;
            margin-top: .5rem;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .listview__attrs > span {
            padding: .55rem .7rem;
            border: 1px solid #e9ecef;
            display: inline-block;
            line-height: 100%;
            font-size: .9rem;
            margin: .2rem .25rem .055rem 0;
            background-color: #FFF
        }

        .listview__checkbox {
            margin-right: .5rem
        }

        .listview:not(.listview--inverse) .listview__item--active, .listview:not(.listview--inverse).listview--hover .listview__item:hover, .listview:not(.listview--inverse).listview--striped .listview__item:nth-child(even) {
            background-color: #f9f9f9
        }

        .listview:not(.listview--inverse).listview--bordered .listview__item + .listview__item {
            border-top: 1px solid #f6f6f6
        }

        .listview--inverse.listview--striped .listview__item:nth-child(even) {
            background-color: rgba(255, 255, 255, .1)
        }

        .listview__actions {
            margin-left: auto;
            -ms-flex-item-align: start;
            align-self: flex-start;
            margin-right: -1rem
        }

        .toolbar {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row;
            height: 5rem;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            padding: .05rem 2.2rem 0;
            position: relative
        }

        .toolbar:not(.toolbar--inner) {
            background-color: #FFF;
            border-radius: 2px;
            margin-bottom: 30px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075)
        }

        .toolbar .actions {
            margin: .05rem -.8rem 0 auto
        }

        .toolbar--inner {
            border-bottom: 1px solid #f6f6f6;
            margin-bottom: 1rem;
            border-radius: 2px 2px 0 0
        }

        .toolbar__nav {
            white-space: nowrap;
            overflow-x: auto
        }

        .toolbar__nav > a {
            color: #9c9c9c;
            display: inline-block;
            transition: color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .toolbar__nav > a {
                transition: none
            }
        }

        .toolbar__nav > a + a {
            padding-left: 1rem
        }

        .toolbar__nav > a.active, .toolbar__nav > a:hover {
            color: #333
        }

        .toolbar__search {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #FFF;
            border-radius: 2px;
            padding-left: 3rem;
            display: none
        }

        .toolbar__search input[type=text] {
            width: 100%;
            height: 100%;
            border: 0;
            padding: 0 1.6rem;
            font-size: 1.1rem;
            color: #495057
        }

        .toolbar__search input[type=text]::-webkit-input-placeholder {
            color: #9c9c9c
        }

        .toolbar__search input[type=text]:-moz-placeholder {
            color: #9c9c9c
        }

        .toolbar__search input[type=text]::-moz-placeholder {
            color: #9c9c9c
        }

        .toolbar__search input[type=text]:-ms-input-placeholder {
            color: #9c9c9c
        }

        .toolbar__search__close {
            transition: color .3s;
            cursor: pointer;
            position: absolute;
            top: 1.9rem;
            left: 1.8rem;
            font-size: 1.5rem;
            color: #9c9c9c
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .toolbar__search__close {
                transition: none
            }
        }

        .toolbar__search__close:hover {
            color: #747a80
        }

        .toolbar__label {
            margin: 0;
            font-size: 1.1rem
        }

        .page-loader {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f3f3f3;
            z-index: 999999999;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex
        }

        .page-loader__spinner {
            position: relative;
            width: 50px;
            height: 50px
        }

        .page-loader__spinner svg {
            -webkit-animation: rotate 2s linear infinite;
            animation: rotate 2s linear infinite;
            -webkit-transform-origin: center center;
            transform-origin: center center;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0
        }

        .page-loader__spinner svg circle {
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
            -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
            animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
            stroke-linecap: round
        }

        .login__block, .select2-dropdown {
            -webkit-animation-fill-mode: both
        }

        @-webkit-keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        @keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        @-webkit-keyframes dash {
            0% {
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0
            }
            50% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -35px
            }
            100% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -124px
            }
        }

        @keyframes dash {
            0% {
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0
            }
            50% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -35px
            }
            100% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -124px
            }
        }

        @-webkit-keyframes color {
            0%, 100% {
                stroke: #ff6b68
            }
            40% {
                stroke: #2196F3
            }
            66% {
                stroke: #32c787
            }
            80%, 90% {
                stroke: #FF9800
            }
        }

        @keyframes color {
            0%, 100% {
                stroke: #ff6b68
            }
            40% {
                stroke: #2196F3
            }
            66% {
                stroke: #32c787
            }
            80%, 90% {
                stroke: #FF9800
            }
        }

        @media (min-width: 768px) {
            .profile {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-direction: row;
                flex-direction: row
            }
        }

        .profile__img {
            padding: 5px;
            position: relative
        }

        .profile__img img {
            max-width: 200px;
            border-radius: 2px
        }

        @media (max-width: 767.98px) {
            .profile {
                margin-top: 75px;
                text-align: center
            }

            .profile__img img {
                margin: -55px 0 -10px;
                width: 120px;
                border: 5px solid #FFF;
                border-radius: 50%
            }
        }

        .profile__img__edit {
            position: absolute;
            font-size: 1.2rem;
            top: 15px;
            left: 15px;
            background-color: rgba(0, 0, 0, .4);
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            color: #FFF
        }

        .profile__img__edit:hover {
            background-color: rgba(0, 0, 0, .65);
            color: #FFF
        }

        .profile__info {
            padding: 30px
        }

        .photos {
            margin: 0 -3px 1rem
        }

        .photos > a {
            padding: 0;
            border: 3px solid transparent
        }

        .photos > a img {
            border-radius: 2px;
            width: 100%
        }

        @media (max-width: 575.98px) {
            .contacts {
                margin: 0 -5px
            }

            .contacts > [class*=col-] {
                padding: 0 5px
            }
        }

        .contacts__item {
            background-color: #FFF;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            padding: 1.8rem 1.5rem 1.15rem;
            margin-bottom: 30px
        }

        .contacts__item:hover .contacts__img > img {
            -webkit-transform: scale(1.1);
            transform: scale(1.1)
        }

        @media (max-width: 575.98px) {
            .contacts__item {
                margin-bottom: 10px
            }
        }

        .contacts__img {
            display: block;
            margin-bottom: 1.1rem
        }

        .contacts__img > img {
            max-width: 120px;
            max-height: 120px;
            width: 100%;
            border-radius: 50%;
            transition: -webkit-transform .3s;
            transition: transform .3s;
            transition: transform .3s, -webkit-transform .3s
        }

        .contacts__info small, .contacts__info strong {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block
        }

        .contacts__info strong {
            font-weight: 400;
            color: #333
        }

        .contacts__info small {
            font-size: .9rem;
            color: #9c9c9c
        }

        .contacts__btn {
            margin-top: 1rem;
            font-weight: 500;
            font-size: .9rem;
            border: 0;
            line-height: 100%;
            background-color: transparent;
            color: #747a80;
            cursor: pointer;
            padding: .75rem 1rem;
            border-radius: 2px;
            transition: background-color .3s, color .3s
        }

        .contacts__btn:hover {
            background-color: #f6f6f6;
            color: #333
        }

        .new-contact__header {
            background-color: #f3f3f3;
            padding: 2.5rem 0;
            border-radius: 2px 2px 0 0;
            border: .35rem solid #FFF;
            position: relative
        }

        .new-contact__img {
            border-radius: 50%;
            box-shadow: 0 0 0 .35rem #FFF;
            width: 150px;
            height: 150px
        }

        @media (max-width: 767.98px) {
            .new-contact__img {
                width: 100px;
                height: 100px
            }
        }

        .new-contact__upload {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            line-height: 42px;
            background-color: #ced4da;
            color: #FFF;
            transition: background-color .3s
        }

        .new-contact__upload:hover {
            color: #FFF;
            background-color: #adb5bd
        }

        .groups__item, .messages {
            background-color: #FFF;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075)
        }

        @media (max-width: 575.98px) {
            .groups {
                margin: 0 -5px
            }

            .groups [class*=col-] {
                padding: 0 5px
            }

            .groups .groups__item {
                margin-bottom: 10px
            }
        }

        .groups__item {
            position: relative;
            padding: 2rem 1rem 1.5rem;
            margin-bottom: 30px
        }

        .groups__item:hover .actions {
            opacity: 1
        }

        .groups__item .actions {
            position: absolute;
            top: .7rem;
            right: .5rem;
            z-index: 1;
            opacity: 0
        }

        .groups__img {
            width: 6.2rem;
            display: inline-block
        }

        .groups__img .avatar-img {
            display: inline-block;
            margin: 0 -1px 3px 0;
            vertical-align: top
        }

        .groups__info {
            margin-top: 1rem
        }

        .groups__info > strong {
            color: #333;
            display: block;
            font-weight: 500
        }

        .messages, .messages__body {
            display: -webkit-box;
            display: -ms-flexbox;
            -webkit-box-direction: normal
        }

        .groups__info > small {
            font-size: .9rem;
            color: #9c9c9c
        }

        .messages {
            display: flex;
            -ms-flex-direction: row;
            flex-direction: row;
            height: calc(100vh - 180px)
        }

        .messages__sidebar {
            width: 23rem;
            overflow: hidden
        }

        @media (max-width: 991.98px) {
            .messages__sidebar {
                display: none
            }
        }

        .messages__sidebar .listview {
            height: calc(100% - 130px);
            overflow-y: auto
        }

        .messages__search {
            padding: 0 2.2rem;
            position: relative
        }

        .messages__search .form-group:before {
            font-family: Material-Design-Iconic-Font;
            content: "";
            font-size: 1.3rem;
            position: absolute;
            left: 0;
            bottom: .15rem
        }

        .messages__search .form-control {
            padding-left: 2rem
        }

        .messages__body {
            -webkit-box-flex: 2;
            -ms-flex: 2;
            flex: 2;
            -webkit-box-orient: vertical;
            -ms-flex-direction: column;
            flex-direction: column;
            display: flex
        }

        .messages__header, .messages__reply {
            -webkit-box-flex: 0;
            -ms-flex: 0 0 auto;
            flex: 0 0 auto
        }

        .messages__content {
            position: relative;
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            overflow: hidden;
            height: 100%
        }

        @media (min-width: 768px) {
            .messages__sidebar {
                border-right: 1px solid #f6f6f6
            }

            .messages__content {
                padding: 2.5rem
            }
        }

        @media (max-width: 767.98px) {
            .messages__content {
                padding: 1.5rem
            }
        }

        .messages__item {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 2rem
        }

        .messages__item:not(.messages__item--right) {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row
        }

        .messages__item:not(.messages__item--right) .messages__details {
            padding-left: 1rem
        }

        .messages__details {
            max-width: 500px
        }

        .messages__details > p {
            border-radius: 2px;
            padding: 1rem 1.3rem;
            margin-bottom: 0;
            display: inline-block;
            text-align: left
        }

        .messages__details > p + p {
            margin-top: 2px
        }

        .messages__details > small {
            display: block;
            padding: 0 1rem;
            margin-top: 1rem;
            color: #9c9c9c;
            font-size: .9rem
        }

        .messages__details > small > .zmdi {
            font-size: 1.2rem;
            vertical-align: middle;
            margin-right: .3rem
        }

        .messages__details:not(.messages__details--highlight) > p {
            background-color: #f9f9f9
        }

        .messages__item--right {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: reverse;
            -ms-flex-direction: row-reverse;
            flex-direction: row-reverse
        }

        .error, .widget-pie {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal
        }

        .error, .login {
            -webkit-box-align: center
        }

        .messages__item--right .messages__details {
            text-align: right
        }

        .messages__item--right .messages__details > p {
            background-color: #2196F3;
            color: #FFF;
            margin-left: auto
        }

        .messages__reply {
            border-top: 1px solid #f6f6f6;
            position: relative
        }

        .messages__reply__text {
            height: 50px;
            width: 100%;
            margin-bottom: -5px;
            border: 0;
            border-radius: 2px;
            padding: 1rem 1.5rem;
            resize: none;
            background-color: transparent;
            color: #495057
        }

        .price-table {
            text-align: center
        }

        .price-table:not(.price-table--highlight) {
            margin: 0 -10px
        }

        .price-table:not(.price-table--highlight) > [class*=col-] {
            padding: 0 10px;
            text-align: center
        }

        .price-table--highlight {
            margin: 0
        }

        .price-table--highlight > [class*=col-] {
            padding: 0
        }

        .price-table__item {
            margin-bottom: 20px;
            background-color: #FFF;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075)
        }

        @media (max-width: 767.98px) {
            .price-table__item {
                max-width: 400px;
                margin-left: auto;
                margin-right: auto
            }
        }

        @media (min-width: 768px) {
            .price-table__item--popular {
                padding-bottom: 1rem;
                position: relative;
                z-index: 1;
                margin: -1rem -.1rem 0;
                box-shadow: 0 0 20px rgba(0, 0, 0, .14)
            }

            .price-table__item--popular .price-table__header {
                padding: 2.5rem 2rem
            }
        }

        .price-table__header {
            color: #FFF;
            border-radius: 2px 2px 0 0;
            padding: 2rem;
            margin-bottom: 2rem
        }

        .price-table__title {
            font-weight: 500;
            font-size: 1.3rem
        }

        .price-table__desc {
            color: rgba(255, 255, 255, .75);
            margin: .3rem 0
        }

        .price-table__price {
            font-size: 1.8rem
        }

        .price-table__price > small {
            font-size: 1rem;
            position: relative;
            top: -.4rem
        }

        .price-table__info {
            padding: 1rem 0
        }

        .price-table__info > li {
            font-weight: 500;
            padding: 1rem 1.5rem
        }

        .price-table__info > li + li {
            border-top: 1px solid #f6f6f6
        }

        .price-table__action {
            display: inline-block;
            margin-bottom: 2.5rem;
            padding: .8rem 1.2rem;
            border-radius: 2px;
            color: #FFF;
            font-weight: 500;
            box-shadow: 0 3px 5px rgba(0, 0, 0, .12);
            transition: opacity .3s
        }

        .invoice, .login__block {
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            background-color: #FFF
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .price-table__action {
                transition: none
            }
        }

        .price-table__action:focus, .price-table__action:hover {
            opacity: .9;
            color: #FFF
        }

        .invoice {
            min-width: 1100px;
            max-width: 1170px;
            padding: 2.5rem;
            border-radius: 2px
        }

        .invoice__header {
            padding: 1.5rem;
            text-align: center;
            border-radius: 2px 2px 0 0;
            margin-bottom: 1.5rem
        }

        .invoice__address {
            margin-bottom: 4rem
        }

        .invoice__address h4 {
            font-weight: 400;
            margin-bottom: 1rem
        }

        .invoice__attrs {
            margin-bottom: 2.5rem
        }

        .invoice__attrs__item {
            padding: 1.5rem 2rem;
            border-radius: 2px;
            text-align: center;
            border: 1px solid #f6f6f6
        }

        .invoice__attrs__item small {
            margin-bottom: .2rem;
            display: block;
            font-size: 1rem
        }

        .invoice__attrs__item h3 {
            margin: 0;
            line-height: 100%;
            font-weight: 400
        }

        .invoice__table {
            margin-bottom: 4rem
        }

        .invoice__footer {
            text-align: center;
            margin: 4rem 0 1.5rem
        }

        .invoice__footer > a {
            color: #747a80
        }

        @media print {
            @page {
                margin: 0;
                size: auto
            }

            body {
                margin: 0 !important;
                padding: 0 !important
            }

            .actions, .btn--action, .chat, .content__title, .footer, .growl-animated, .header, .navigation, .notifications {
                display: none !important
            }

            .invoice {
                padding: 30px !important;
                -webkit-print-color-adjust: exact !important
            }
        }

        .login {
            min-height: 100vh;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            padding-top: 1.2rem
        }

        .login__block {
            max-width: 330px;
            width: 100%;
            display: none;
            text-align: center;
            padding: 1.2rem;
            -webkit-animation-name: fadeInUp;
            animation-name: fadeInUp;
            animation-duration: .3s;
            animation-fill-mode: both;
            border-radius: 2px
        }

        @media (min-width: 576px) {
            .login__block:hover .login__block__actions .dropdown {
                display: block
            }
        }

        .login__block.active {
            z-index: 10;
            display: inline-block
        }

        .login__block__header {
            padding: 1.5rem;
            margin-top: -2.4rem;
            position: relative;
            color: #FFF;
            border-radius: 2px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, .18)
        }

        .login__block__header > i, .login__block__header > img {
            display: block;
            margin-bottom: .8rem
        }

        .login__block__header > i {
            font-size: 3rem
        }

        .login__block__header > img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, .33)
        }

        .login__block__actions {
            position: absolute;
            bottom: 1rem;
            right: 1rem
        }

        .login__block__actions .dropdown:not(.show) {
            display: none
        }

        .login__block__body {
            padding: 1rem
        }

        .login__block__btn {
            margin-top: .5rem
        }

        .login__block__btn, .login__block__btn:focus, .login__block__btn:hover {
            color: #FFF
        }

        .login__block__btn:hover {
            opacity: .9
        }

        .todo__item {
            padding-left: 60px;
            display: block;
            position: relative
        }

        .todo__item > .checkbox__char {
            position: absolute;
            left: 0;
            top: 0
        }

        .todo__item small {
            display: block;
            font-size: .95rem;
            margin-top: .2rem
        }

        .todo__item > input[type=checkbox]:checked ~ .listview__attrs, .todo__item > input[type=checkbox]:checked ~ .listview__content {
            text-decoration: line-through
        }

        .notes__item {
            margin-bottom: 30px
        }

        .notes__item > a {
            height: 155px;
            background-color: #FFF;
            display: block;
            padding: 1.8rem 2rem;
            position: relative;
            color: #747a80;
            transition: background-color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .notes__item > a {
                transition: none
            }
        }

        .notes__item > a, .notes__item > a:before {
            border-radius: 2px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .08)
        }

        .notes__item > a:before {
            content: '';
            position: absolute;
            width: calc(100% - 10px);
            bottom: -5px;
            left: 5px;
            z-index: -1;
            height: 20px;
            background-color: #FFF;
            transition: bottom .2s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .notes__item > a:before {
                transition: none
            }
        }

        .notes__item:hover > a:before {
            bottom: -8px
        }

        .notes__item:hover .notes__actions {
            opacity: 1
        }

        .notes__title {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .notes__actions {
            position: absolute;
            right: 2.2rem;
            bottom: 1rem;
            font-size: 1.1rem;
            width: 2.2rem;
            height: 2.2rem;
            text-align: center;
            background: rgba(0, 0, 0, .7);
            border-radius: 50%;
            line-height: 2.2rem;
            color: #FFF;
            box-shadow: 0 0 4px rgba(0, 0, 0, .5);
            opacity: 0;
            transition: opacity .3s;
            cursor: pointer
        }

        .blog__arthur-social > a:hover, .team__social > a:hover {
            opacity: .9
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .notes__actions {
                transition: none
            }
        }

        .notes__actions:hover {
            background: rgba(0, 0, 0, .9)
        }

        .note-view .trumbowyg-box {
            border: 0
        }

        .note-view__field {
            border-bottom: 1px solid #eceff1
        }

        .note-view__field input {
            border: 0;
            font-size: 1rem;
            padding: 1.7rem 2rem;
            height: auto
        }

        .note-view__field--color {
            padding: 1.2rem 2rem .8rem
        }

        .note-view__label {
            float: left;
            margin: .4rem 1.5rem 0 0
        }

        [data-ma-theme=red] .header, [data-ma-theme=red] .login__block__btn, [data-ma-theme=red] .login__block__header, [data-ma-theme=red] .top-menu {
            background-color: #ff6b68
        }

        [data-ma-theme=red] .navigation__active:before, [data-ma-theme=red] .navigation__active > a, [data-ma-theme=red] .navigation__sub--active > a {
            color: #ff6b68
        }

        [data-ma-theme=purple] .header, [data-ma-theme=purple] .login__block__btn, [data-ma-theme=purple] .login__block__header, [data-ma-theme=purple] .top-menu {
            background-color: #d066e2
        }

        [data-ma-theme=purple] .navigation__active:before, [data-ma-theme=purple] .navigation__active > a, [data-ma-theme=purple] .navigation__sub--active > a {
            color: #d066e2
        }

        [data-ma-theme=indigo] .header, [data-ma-theme=indigo] .login__block__btn, [data-ma-theme=indigo] .login__block__header, [data-ma-theme=indigo] .top-menu {
            background-color: #3F51B5
        }

        [data-ma-theme=indigo] .navigation__active:before, [data-ma-theme=indigo] .navigation__active > a, [data-ma-theme=indigo] .navigation__sub--active > a {
            color: #3F51B5
        }

        [data-ma-theme=blue] .header, [data-ma-theme=blue] .login__block__btn, [data-ma-theme=blue] .login__block__header, [data-ma-theme=blue] .top-menu {
            background-color: #2196F3
        }

        [data-ma-theme=blue] .navigation__active:before, [data-ma-theme=blue] .navigation__active > a, [data-ma-theme=blue] .navigation__sub--active > a {
            color: #2196F3
        }

        [data-ma-theme=cyan] .header, [data-ma-theme=cyan] .login__block__btn, [data-ma-theme=cyan] .login__block__header, [data-ma-theme=cyan] .top-menu {
            background-color: #00BCD4
        }

        [data-ma-theme=cyan] .navigation__active:before, [data-ma-theme=cyan] .navigation__active > a, [data-ma-theme=cyan] .navigation__sub--active > a {
            color: #00BCD4
        }

        [data-ma-theme=teal] .header, [data-ma-theme=teal] .login__block__btn, [data-ma-theme=teal] .login__block__header, [data-ma-theme=teal] .top-menu {
            background-color: #39bbb0
        }

        [data-ma-theme=teal] .navigation__active:before, [data-ma-theme=teal] .navigation__active > a, [data-ma-theme=teal] .navigation__sub--active > a {
            color: #39bbb0
        }

        [data-ma-theme=green] .header, [data-ma-theme=green] .login__block__btn, [data-ma-theme=green] .login__block__header, [data-ma-theme=green] .top-menu {
            background-color: #32c787
        }

        [data-ma-theme=green] .navigation__active:before, [data-ma-theme=green] .navigation__active > a, [data-ma-theme=green] .navigation__sub--active > a {
            color: #32c787
        }

        [data-ma-theme=brown] .header, [data-ma-theme=brown] .login__block__btn, [data-ma-theme=brown] .login__block__header, [data-ma-theme=brown] .top-menu {
            background-color: #795548
        }

        [data-ma-theme=brown] .navigation__active:before, [data-ma-theme=brown] .navigation__active > a, [data-ma-theme=brown] .navigation__sub--active > a {
            color: #795548
        }

        [data-ma-theme=orange] .header, [data-ma-theme=orange] .login__block__btn, [data-ma-theme=orange] .login__block__header, [data-ma-theme=orange] .top-menu {
            background-color: #FF9800
        }

        [data-ma-theme=orange] .navigation__active:before, [data-ma-theme=orange] .navigation__active > a, [data-ma-theme=orange] .navigation__sub--active > a {
            color: #FF9800
        }

        [data-ma-theme=blue-grey] .header, [data-ma-theme=blue-grey] .login__block__btn, [data-ma-theme=blue-grey] .login__block__header, [data-ma-theme=blue-grey] .top-menu {
            background-color: #607D8B
        }

        [data-ma-theme=blue-grey] .navigation__active:before, [data-ma-theme=blue-grey] .navigation__active > a, [data-ma-theme=blue-grey] .navigation__sub--active > a {
            color: #607D8B
        }

        .theme-switch .btn-group--colors {
            display: block;
            margin-top: .75rem
        }

        .error, .q-a__info {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .ie-warning {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #FFF;
            z-index: 1000000;
            text-align: center;
            padding: 3rem;
            overflow: auto
        }

        .ie-warning > h1 {
            font-size: 2rem
        }

        .ie-warning p {
            font-size: 1.2rem;
            color: #9c9c9c
        }

        .ie-warning__downloads {
            background-color: #f6f6f6;
            padding: 30px 0;
            margin: 30px 0
        }

        .ie-warning__downloads > a {
            padding: 0 10px
        }

        .error {
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-direction: row;
            flex-direction: row;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            height: 100vh;
            width: 100%
        }

        .error__inner {
            max-width: 600px;
            width: 100%;
            padding: 1rem;
            text-align: center
        }

        .error__inner > h1 {
            font-size: 8rem;
            font-weight: 700;
            color: #FFF;
            text-shadow: 0 0 10px rgba(0, 0, 0, .03);
            line-height: 100%;
            margin-bottom: 1.5rem
        }

        .error__inner > h2 {
            font-weight: 400;
            margin: 1.3rem 0;
            font-size: 1.5rem
        }

        .results__header {
            padding: 2rem 2rem 0;
            border-radius: 2px 2px 0 0;
            margin-bottom: 2rem;
            background-color: #f9f9f9
        }

        .results__search {
            position: relative
        }

        .results__search input[type=text] {
            width: 100%;
            border: 0;
            border-radius: 2px;
            background-color: #FFF;
            color: #495057;
            padding: 0 1rem 0 3rem;
            height: 2.9rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 2px rgba(0, 0, 0, .08);
            transition: box-shadow .3s
        }

        .results__search input[type=text]::-webkit-input-placeholder {
            color: #868e96
        }

        .results__search input[type=text]:-moz-placeholder {
            color: #868e96
        }

        .results__search input[type=text]::-moz-placeholder {
            color: #868e96
        }

        .results__search input[type=text]:-ms-input-placeholder {
            color: #868e96
        }

        .results__search input[type=text]:focus {
            box-shadow: 0 7px 12px rgba(0, 0, 0, .125)
        }

        .results__search:before {
            font-family: Material-Design-Iconic-Font;
            content: "";
            font-size: 1.3rem;
            position: absolute;
            top: .55rem;
            left: 1.1rem;
            z-index: 1
        }

        .quick-stats__item::after, .widget-pictures__body::after, .widget-pie::after, .widget-ratings__item::after, .widget-visitors__stats::after {
            content: ""
        }

        .results__nav {
            border: 0
        }

        .issue-tracker .listview__item {
            position: relative;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        @media (max-width: 767.98px) {
            .issue-tracker .listview__item {
                display: block
            }
        }

        .issue-tracker__item:not(.actions) {
            margin-left: 2rem
        }

        .issue-tracker__item > .zmdi {
            font-size: 1.15rem;
            vertical-align: top;
            position: relative;
            top: .25rem;
            margin-right: .5rem
        }

        .issue-tracker__item.actions {
            margin: 0 -1rem 0 1rem
        }

        .issue-tracker__tag {
            padding: .3rem .75rem .4rem;
            line-height: 100%;
            font-size: .95rem;
            border-radius: 2px;
            color: #FFF
        }

        .blog__arthur-social > a, .team__social > a {
            line-height: 36px;
            color: #FFF;
            transition: opacity .3s, background-color .3s;
            display: inline-block
        }

        .team {
            margin-top: 7rem
        }

        .team__item {
            text-align: center;
            margin-bottom: 7rem
        }

        @media (max-width: 767.98px) {
            .team__item {
                max-width: 365px;
                margin: 0 auto 80px
            }
        }

        .team__item .card-subtitle {
            margin-bottom: 1rem
        }

        .team__img {
            display: inline-block;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            border: 7px solid #FFF;
            margin: -4rem auto -.5rem
        }

        .team__social {
            margin-top: 2rem
        }

        .team__social > a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #f9f9f9;
            font-size: 1.2rem;
            margin: 0 1px
        }

        .blog__tags {
            text-align: center;
            background-color: #f9f9f9;
            padding: 2rem 1rem 1.5rem;
            margin: 2rem 0 .5rem
        }

        .blog__arthur {
            padding: 2rem 2rem 2.5rem;
            text-align: center
        }

        .blog__arthur-img {
            margin-bottom: 1.5rem
        }

        .blog__arthur-img > img {
            width: 100px;
            height: 100px;
            border-radius: 50%
        }

        .blog__arthur-social {
            margin: 2rem 0 0
        }

        .blog__arthur-social > a {
            width: 35px;
            height: 35px;
            text-align: center;
            border-radius: 50%;
            font-size: 1.2rem;
            margin: 0 1px
        }

        .q-a__item {
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start
        }

        .q-a__stat {
            margin: .35rem 2rem 0 0;
            -ms-flex-item-align: start;
            align-self: flex-start
        }

        .q-a__stat > span {
            display: inline-block;
            width: 70px;
            border-radius: 2px;
            background-color: #f6f6f6;
            text-align: center;
            padding: .9rem .5rem .65rem;
            margin-right: .2rem
        }

        .q-a__stat > span > strong {
            display: block;
            font-size: 1.2rem;
            font-weight: 400;
            line-height: 100%;
            color: #333;
            margin: .1rem 0 .075rem
        }

        .q-a__stat > span > small {
            line-height: 100%
        }

        .q-a__question {
            position: relative;
            margin-bottom: 3rem
        }

        @media (min-width: 768px) {
            .q-a__question {
                margin-top: 2rem
            }
        }

        @media (min-width: 576px) {
            .q-a__question {
                padding-left: 100px
            }
        }

        .q-a__question > h2 {
            font-size: 1.25rem;
            font-weight: 400
        }

        .q-a__question > h2 + p {
            margin-top: 1rem
        }

        .q-a__vote {
            position: absolute;
            left: 0;
            top: 0;
            text-align: center
        }

        .q-a__vote > i {
            font-size: 1.25rem;
            cursor: pointer
        }

        .q-a__vote__votes {
            padding: .5rem 0;
            background-color: #FFF;
            box-shadow: 0 2px 1px rgba(0, 0, 0, .05);
            border-radius: 2px;
            width: 75px;
            font-size: 1.2rem;
            margin-bottom: .35rem;
            color: #333;
            font-weight: 400
        }

        .q-a__info {
            margin-top: 1.5rem;
            padding: 1.25rem 0;
            border-top: 1px solid #e9ecef;
            position: relative;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .q-a__info .actions {
            margin: 0 -.5rem 0 auto
        }

        .q-a__op > a > img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: .5rem
        }

        .q-a__vote-answer {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-left: auto;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center
        }

        .widget-past-days {
            background-color: #00BCD4;
            overflow: hidden
        }

        .widget-past-days__main {
            margin: 0 -10px
        }

        .widget-past-days__chart {
            opacity: .75;
            margin: .55rem 0 0 auto
        }

        .widget-past-days__info small {
            font-size: 1rem;
            color: rgba(255, 255, 255, .9)
        }

        .widget-past-days__info h3 {
            margin: 0;
            color: #FFF;
            font-weight: 400
        }

        .widget-visitors__stats {
            margin: 0 -.5rem 2rem
        }

        .widget-visitors__stats::after {
            display: block;
            clear: both
        }

        .widget-visitors__stats > div {
            border: 1px solid #f6f6f6;
            padding: 1.2rem 1.5rem 1.1rem;
            float: left;
            margin: 0 .5rem;
            width: calc(50% - 1rem)
        }

        .widget-visitors__stats > div > strong {
            font-size: 1.3rem;
            font-weight: 400;
            line-height: 100%;
            color: #333
        }

        .widget-visitors__stats > div > small {
            display: block;
            color: #9c9c9c;
            font-size: 1rem;
            line-height: 100%;
            margin-top: .45rem
        }

        .widget-visitors__map {
            width: 100%;
            height: 250px
        }

        .widget-visitors__country {
            height: 1rem;
            width: 1.5rem;
            vertical-align: top;
            position: relative;
            margin-right: .25rem;
            left: -.1rem;
            border-radius: 1px
        }

        .widget-pie {
            background-color: #ff6b68;
            -ms-flex-direction: row;
            flex-direction: row;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap
        }

        .widget-pie::after {
            display: block;
            clear: both
        }

        .widget-pie__item {
            padding: 20px 0;
            text-align: center
        }

        .widget-pie__item:nth-child(2n) {
            background-color: rgba(255, 255, 255, .1)
        }

        .widget-pie__title {
            color: #FFF
        }

        .quick-stats__item {
            padding: 1.5rem 1.5rem 1.45rem;
            border-radius: 2px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .08);
            margin-bottom: 30px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: baseline;
            -ms-flex-align: baseline;
            align-items: baseline
        }

        .quick-stats__item::after {
            display: block;
            clear: both
        }

        .quick-stats__chart, .quick-stats__info {
            display: inline-block;
            vertical-align: middle
        }

        .quick-stats__info {
            min-width: 0
        }

        .quick-stats__info > h2, .quick-stats__info > small {
            line-height: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .quick-stats__info > h2 {
            font-weight: 400;
            margin: 0;
            font-size: 1.3rem;
            color: #FFF
        }

        .quick-stats__info > small {
            font-size: 1rem;
            display: block;
            color: rgba(255, 255, 255, .8);
            margin-top: .6rem
        }

        .quick-stats__chart {
            margin-left: auto;
            padding-left: 1.2rem
        }

        @media (min-width: 576px) and (max-width: 1199.98px) {
            .quick-stats__chart {
                display: none
            }
        }

        .stats {
            padding-top: 1rem
        }

        .stats__item {
            background-color: #FFF;
            border-radius: 2px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
            margin-bottom: 30px;
            padding: 1rem
        }

        .stats__chart {
            border-radius: 2px;
            padding-top: 2rem;
            margin-top: -2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .1);
            overflow: hidden
        }

        .widget-search, .widget-time {
            box-shadow: 0 1px 2px rgba(0, 0, 0, .075)
        }

        .stats__chart .flot-chart {
            margin: 0 -12px -12px
        }

        .stats__info {
            padding: 1.8rem 1rem .5rem;
            position: relative;
            text-align: center
        }

        .stats__info h2 {
            font-size: 1.3rem;
            margin: 0
        }

        .stats__info small {
            display: block;
            font-size: 1rem;
            margin-top: .4rem;
            color: #9c9c9c
        }

        .widget-pictures__body {
            margin: 0;
            padding: 2px;
            text-align: center
        }

        .widget-pictures__body::after {
            display: block;
            clear: both
        }

        .widget-pictures__body > a {
            padding: 2px;
            display: block
        }

        .widget-pictures__body > a img {
            width: 100%;
            border-radius: 2px
        }

        .widget-pictures__body > a:hover {
            opacity: .9
        }

        .widget-ratings__star {
            font-size: 1.5rem;
            color: #e9ecef;
            margin: .5rem 0 0
        }

        .widget-ratings__star .active {
            color: #ffc721
        }

        .widget-ratings__item {
            padding: .5rem 0
        }

        .widget-ratings__item::after {
            display: block;
            clear: both
        }

        .widget-ratings__item .float-left, .widget-ratings__item .float-right {
            font-size: 1.15rem
        }

        .widget-ratings__item .float-left .zmdi {
            font-size: 1.5rem;
            vertical-align: top;
            color: #ffc721;
            position: relative;
            top: .15rem;
            margin-left: .35rem
        }

        .widget-ratings__item:last-child {
            padding-bottom: 0
        }

        .widget-ratings__progress {
            overflow: hidden;
            padding: .6rem 1.5rem
        }

        .widget-profile {
            background-color: #03A9F4
        }

        .widget-profile .avatar-char {
            background-color: rgba(255, 255, 255, .25);
            color: #FFF;
            margin-right: 1.2rem
        }

        .widget-profile__img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 1.2rem;
            border: 5px solid rgba(255, 255, 255, .1)
        }

        .widget-profile__list {
            color: #FFF
        }

        .widget-profile__list .media {
            padding: 1rem 2rem
        }

        .widget-profile__list .media:nth-child(odd) {
            background-color: rgba(255, 255, 255, .1)
        }

        .widget-profile__list .media-body strong {
            display: block;
            font-weight: 500
        }

        .widget-profile__list .media-body small {
            color: rgba(255, 255, 255, .8);
            font-size: .92rem
        }

        .widget-contacts__map {
            display: block;
            padding: 3px 3px 4px
        }

        .widget-contacts__map img {
            width: 100%;
            border-radius: 2px;
            margin: -20px 0 -1px
        }

        .widget-signups {
            background-color: #607D8B
        }

        .widget-signups__list {
            text-align: center
        }

        .widget-signups__list > a {
            vertical-align: top;
            margin: 4px 2px;
            display: inline-block
        }

        .widget-signups__list .avatar-char {
            background-color: rgba(255, 255, 255, .1);
            color: #FFF
        }

        .widget-signups__list .avatar-char, .widget-signups__list .avatar-img {
            margin: 0
        }

        .widget-time {
            padding: 2rem;
            border-radius: 2px;
            margin-bottom: 30px
        }

        .widget-time .time {
            font-size: 2rem;
            text-align: center
        }

        .widget-time .time > span {
            padding: 1rem 1.5rem;
            background-color: rgba(255, 255, 255, .2);
            border-radius: 2px;
            display: inline-block;
            margin: 0 .25rem;
            position: relative;
            color: #FFF
        }

        .widget-time .time > span:after {
            position: absolute;
            right: -13px;
            top: 10px
        }

        .widget-search {
            border-radius: 2px;
            margin-bottom: 30px;
            position: relative
        }

        .widget-search > i {
            top: 1.3rem;
            left: 1.5rem;
            position: absolute;
            font-size: 1.5rem
        }

        .widget-search:not(.widget-search__inverse) {
            background-color: #FFF
        }

        .widget-search--inverse, .widget-search--inverse .widget-search__input {
            color: #FFF
        }

        .widget-search--inverse .widget-search__input::-webkit-input-placeholder {
            color: rgba(255, 255, 255, .75)
        }

        .widget-search--inverse .widget-search__input:-moz-placeholder {
            color: rgba(255, 255, 255, .75)
        }

        .widget-search--inverse .widget-search__input::-moz-placeholder {
            color: rgba(255, 255, 255, .75)
        }

        .widget-search--inverse .widget-search__input:-ms-input-placeholder {
            color: rgba(255, 255, 255, .75)
        }

        .widget-search__input {
            border: 0;
            background-color: transparent;
            padding: 0 2rem 0 3.75rem;
            width: 100%;
            height: 4rem;
            font-size: 1.15rem
        }

        .flot-chart {
            height: 200px;
            display: block
        }

        .flot-chart--sm {
            height: 100px
        }

        .flot-chart--xs {
            height: 70px
        }

        .flot-chart-legends {
            text-align: center;
            margin: 20px 0 -10px
        }

        .flot-chart-legends table {
            display: inline-block
        }

        #jqstooltip .jqsfield > span, .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none
        }

        .flot-chart-legends .legendColorBox > div > div {
            border-radius: 50%
        }

        .flot-chart-legends .legendLabel {
            padding: 0 8px 0 3px
        }

        .flot-tooltip {
            position: absolute;
            line-height: 100%;
            color: #747a80;
            display: none;
            font-size: .95rem;
            box-shadow: 0 3px 5px rgba(0, 0, 0, .08);
            border-radius: 2px;
            padding: .7rem 1rem;
            background-color: #FFF;
            z-index: 99999
        }

        #jqstooltip {
            text-align: center;
            padding: 5px 10px;
            border: 0;
            height: auto !important;
            width: auto !important;
            background: #FFF;
            box-shadow: 0 3px 5px rgba(0, 0, 0, .1);
            border-radius: 2px
        }

        #jqstooltip .jqsfield {
            font-size: .95rem;
            font-weight: 500;
            font-family: inherit;
            text-align: center;
            color: #747a80
        }

        .select2-container--default .select2-selection--single {
            border-radius: 0;
            border: 0;
            background-color: transparent;
            border-bottom: 1px solid #eceff1;
            height: auto
        }

        .select2-container--default .select2-selection--single:before {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: 1;
            right: 0;
            bottom: 0px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 8px 8px;
            border-color: transparent transparent #d1d1d1
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #868e96
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            padding: .375rem 2px
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 0
        }

        .select2-container--default .select2-selection--multiple {
            background-color: transparent;
            border: 0;
            box-shadow: 0 1px 0 0 #eceff1;
            border-radius: 0;
            padding-bottom: 1px
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            border-radius: 2px;
            border: 0;
            background-color: #f5f6f8;
            padding: .4rem .8rem;
            color: #495057
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: .5rem
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0
        }

        .select2-container--default.select2-container--disabled .select2-selection--single {
            background-color: transparent;
            opacity: .5
        }

        .select2-container--default .selection {
            position: relative;
            display: block
        }

        .select2-container--default .selection:after, .select2-container--default .selection:before {
            content: '';
            position: absolute;
            height: 2px;
            width: 0;
            bottom: 0;
            transition: all .2s;
            transition-timing-function: ease;
            background-color: #2196F3
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .select2-container--default .selection:after, .select2-container--default .selection:before {
                transition: none
            }
        }

        .select2-container--default .selection:before {
            left: 50%
        }

        .select2-container--default .selection:after {
            right: 50%
        }

        .select2-container--open .selection:after, .select2-container--open .selection:before {
            width: 50%
        }

        .select2-dropdown {
            background-color: #FFF;
            border: 0;
            margin-top: 1px;
            border-radius: 2px;
            padding: .8rem 0;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .11);
            z-index: 19;
            -webkit-animation-name: fadeIn;
            animation-name: fadeIn;
            -webkit-animation-duration: .3s;
            animation-duration: .3s;
            animation-fill-mode: both
        }

        .select2-dropdown .select2-results__option {
            padding: .65rem 1.5rem;
            transition: background-color .3s, color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .select2-dropdown .select2-results__option {
                transition: none
            }
        }

        .select2-dropdown .select2-results__option--highlighted[aria-selected] {
            background-color: #f9f9f9;
            color: #16181b
        }

        .select2-dropdown .select2-results__option[aria-selected=true] {
            position: relative;
            padding-right: 1.5rem;
            background-color: #f9f9f9;
            color: #16181b
        }

        .select2-dropdown .select2-results__option[aria-selected=true]:before {
            font-family: Material-Design-Iconic-Font;
            content: '\f26b';
            position: absolute;
            top: .45rem;
            right: 1.5rem;
            font-size: 1.3rem;
            color: #16181b
        }

        .select2-dropdown .select2-search--dropdown {
            margin-top: -.5rem;
            border-bottom: 1px solid #eceff1;
            position: relative;
            margin-bottom: 1rem
        }

        .select2-dropdown .select2-search--dropdown:before {
            font-family: Material-Design-Iconic-Font;
            content: '\f1c3';
            font-size: 1.5rem;
            color: #747a80;
            position: absolute;
            left: 1.4rem;
            top: .65rem
        }

        .select2-dropdown .select2-search--dropdown .select2-search__field {
            border: 0;
            background-color: transparent;
            height: 2.8rem;
            color: #495057;
            padding-left: 3.5rem
        }

        .dropzone {
            border: 0;
            background-color: #f6f6f6;
            border-radius: 2px;
            transition: border-color .3s, background-color .3s;
            min-height: 50px;
            position: relative
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .dropzone {
                transition: none
            }
        }

        .dropzone:before {
            font-family: Material-Design-Iconic-Font;
            content: '\f22a';
            font-size: 2rem;
            color: #747a80;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            margin: auto;
            width: 50px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            background-color: #FFF;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .15);
            opacity: 0;
            transition: opacity .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .dropzone:before {
                transition: none
            }
        }

        .dropzone .dz-preview.dz-file-preview .dz-image, .dropzone .dz-preview.dz-image-preview .dz-image {
            border-radius: 2px;
            border: 3px solid #FFF;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .15)
        }

        .dropzone .dz-preview .dz-remove {
            position: absolute;
            top: -3px;
            right: -4px;
            z-index: 20;
            font-size: 0;
            width: 22px;
            height: 22px;
            background-color: #ff6b68;
            border-radius: 50%;
            border: 2px solid #FFF
        }

        .dropzone .dz-preview .dz-remove:hover {
            background-color: #ff524f;
            text-decoration: none
        }

        .dropzone .dz-preview .dz-remove:before {
            content: '\f136';
            font-size: .8rem;
            font-family: Material-Design-Iconic-Font;
            color: #FFF;
            font-weight: 700;
            line-height: 19px;
            padding: 0 6px
        }

        .dropzone .dz-message {
            transition: opacity .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .dropzone .dz-message {
                transition: none
            }
        }

        .dropzone .dz-message span {
            font-size: 1rem;
            color: #9ca0a5;
            display: inline-block;
            border-radius: 2px;
            transition: color .3s, box-shadow, .3s;
            padding: .5rem 1.4rem .8rem;
            background-color: #FFF;
            box-shadow: 0 4px 5px rgba(0, 0, 0, .1)
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .dropzone .dz-message span {
                transition: none
            }
        }

        .dropzone .dz-message span:before {
            content: '\f21e';
            font-family: Material-Design-Iconic-Font;
            font-size: 1.6rem;
            display: inline-block;
            position: relative;
            top: 2px;
            margin-right: .8rem
        }

        .dropzone:hover .dz-message span {
            color: #747a80
        }

        .dropzone.dz-drag-hover, .dropzone:hover {
            background-color: #eee
        }

        .dropzone.dz-drag-hover .dz-message {
            opacity: 0
        }

        .dropzone.dz-drag-hover:before {
            opacity: 1
        }

        .noUi-target {
            border-radius: 0;
            box-shadow: none;
            border: 0;
            background: #f6f6f6;
            margin: 15px 0
        }

        .noUi-horizontal {
            height: 2px
        }

        .noUi-vertical {
            width: 3px
        }

        .noUi-connect {
            background: #39bbb0;
            box-shadow: none
        }

        .noUi-horizontal .noUi-handle, .noUi-vertical .noUi-handle {
            border-radius: 50%;
            width: 12px;
            height: 12px;
            cursor: pointer;
            border: 0;
            box-shadow: none;
            background-color: #39bbb0;
            transition: box-shadow .2s, -webkit-transform .2s;
            transition: transform .2s, box-shadow .2s;
            transition: transform .2s, box-shadow .2s, -webkit-transform .2s
        }

        .noUi-horizontal .noUi-handle.noUi-active, .noUi-vertical .noUi-handle.noUi-active {
            -webkit-transform: scale(1.5);
            transform: scale(1.5);
            box-shadow: 0 0 0 8px rgba(0, 0, 0, .04)
        }

        .noUi-horizontal .noUi-handle:after, .noUi-horizontal .noUi-handle:before, .noUi-vertical .noUi-handle:after, .noUi-vertical .noUi-handle:before {
            display: none;
            border: 0
        }

        .noUi-horizontal .noUi-handle {
            right: -6px !important;
            top: -5px
        }

        .noUi-vertical .noUi-handle {
            left: -4px;
            top: -6px
        }

        .input-slider--blue .noUi-connect {
            background: #2196F3
        }

        .input-slider--blue.noUi-horizontal .noUi-handle, .input-slider--blue.noUi-vertical .noUi-handle {
            background-color: #2196F3
        }

        .input-slider--red .noUi-connect {
            background: #ff6b68
        }

        .input-slider--red.noUi-horizontal .noUi-handle, .input-slider--red.noUi-vertical .noUi-handle {
            background-color: #ff6b68
        }

        .input-slider--amber .noUi-connect {
            background: #FF9800
        }

        .input-slider--amber.noUi-horizontal .noUi-handle, .input-slider--amber.noUi-vertical .noUi-handle {
            background-color: #FF9800
        }

        .input-slider--green .noUi-connect {
            background: #32c787
        }

        .input-slider--green.noUi-horizontal .noUi-handle, .input-slider--green.noUi-vertical .noUi-handle {
            background-color: #32c787
        }

        .easy-pie-chart {
            display: inline-block;
            position: relative
        }

        .easy-pie-chart__value {
            position: absolute;
            left: 0;
            top: 0;
            text-align: center;
            width: 100%;
            height: 100%
        }

        .easy-pie-chart__value:after {
            content: "%";
            font-size: 12px
        }

        .easy-pie-chart__title {
            margin-top: -2px;
            line-height: 15px;
            font-size: 11px
        }

        .dataTables_wrapper {
            margin-top: 20px
        }

        .dataTables_wrapper .table {
            margin: 40px 0 20px
        }

        .dataTables_wrapper .table > thead > tr > th {
            cursor: pointer
        }

        .dataTables_wrapper .table > thead > tr > th:hover {
            background-color: #fbfbfb
        }

        .dataTables_wrapper .table > thead > tr > th.sorting_asc, .dataTables_wrapper .table > thead > tr > th.sorting_desc {
            position: relative
        }

        .dataTables_wrapper .table > thead > tr > th.sorting_asc:after, .dataTables_wrapper .table > thead > tr > th.sorting_desc:after {
            font-family: Material-Design-Iconic-Font;
            position: absolute;
            top: .75rem;
            right: 1rem;
            font-size: 1.4rem
        }

        .dataTables_wrapper .table > thead > tr > th.sorting_asc:after {
            content: '\f1cd'
        }

        .dataTables_wrapper .table > thead > tr > th.sorting_desc:after {
            content: '\f1ce'
        }

        .dataTables_filter, .dataTables_length {
            font-size: 0;
            position: relative
        }

        .dataTables_filter:after, .dataTables_length:after {
            font-family: Material-Design-Iconic-Font;
            position: absolute;
            left: 1px;
            bottom: 5px;
            font-size: 1.2rem;
            color: #333
        }

        .dataTables_filter > label, .dataTables_length > label {
            margin: 0;
            width: 100%
        }

        .dataTables_filter > label input[type=search], .dataTables_filter > label select, .dataTables_length > label input[type=search], .dataTables_length > label select {
            padding-left: 25px;
            font-size: 1rem;
            background: 0 0;
            border: 0;
            height: 35px;
            border-radius: 0;
            width: 100%;
            border-bottom: 1px solid #eceff1
        }

        .dataTables_length {
            float: right;
            margin-left: 20px
        }

        .dataTables_length:before {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: 1;
            right: 0;
            bottom: 5px;
            width: 0;
            height: 0;
            border-style: solid;
            border-width: 0 0 8px 8px;
            border-color: transparent transparent #d1d1d1
        }

        @media (max-width: 575.98px) {
            .dataTables_length {
                display: none
            }
        }

        .dataTables_length:after {
            content: '\f197'
        }

        .dataTables_length select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none
        }

        .dataTables_filter {
            overflow: hidden
        }

        .dataTables_filter:after {
            content: '\f1c3'
        }

        .dataTables_filter > label:after, .dataTables_filter > label:before {
            content: '';
            position: absolute;
            height: 2px;
            width: 0;
            bottom: 0;
            transition: width .5s ease;
            background-color: #2196F3
        }

        .dataTables_filter > label:before {
            left: 50%
        }

        .dataTables_filter > label:after {
            right: 50%
        }

        .dataTables_filter--toggled > label:after, .dataTables_filter--toggled > label:before {
            width: 50%
        }

        .dataTables_paginate {
            text-align: center
        }

        .paginate_button {
            background-color: #f3f3f3;
            display: inline-block;
            color: #8e9499;
            vertical-align: top;
            border-radius: 50%;
            margin: 0 1px 0 2px;
            font-size: 1rem;
            cursor: pointer;
            width: 2.5rem;
            height: 2.5rem;
            line-height: 2.5rem;
            text-align: center;
            transition: background-color .3s, color .3s
        }

        .dt-buttons, .flatpickr-calendar:after, .flatpickr-calendar:before {
            display: none
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .paginate_button {
                transition: none
            }
        }

        .paginate_button.current {
            background-color: #03A9F4;
            color: #FFF;
            cursor: default
        }

        .paginate_button:not(.current):not(.disabled):focus, .paginate_button:not(.current):not(.disabled):hover {
            background-color: #e6e6e6;
            color: #5c6165
        }

        .paginate_button.current, .paginate_button.disabled {
            cursor: default
        }

        .paginate_button.next, .paginate_button.previous {
            font-size: 0;
            position: relative
        }

        @media screen and (-ms-high-contrast: active),(-ms-high-contrast: none) {
            .paginate_button.next, .paginate_button.previous {
                font-size: 1rem
            }
        }

        .paginate_button.next:before, .paginate_button.previous:before {
            font-family: Material-Design-Iconic-Font;
            font-size: 1rem;
            line-height: 2.55rem
        }

        .paginate_button.previous:before {
            content: '\F2FF'
        }

        .paginate_button.next:before {
            content: '\F301'
        }

        .paginate_button.disabled {
            opacity: .6
        }

        .paginate_button.disabled:focus, .paginate_button.disabled:hover {
            color: #8e9499
        }

        .dataTables_info {
            text-align: center;
            margin-bottom: 25px;
            font-size: .9rem;
            color: #9c9c9c
        }

        .dataTables_buttons {
            float: right;
            margin: 0 0 0 20px;
            border-bottom: 1px solid #eceff1;
            min-height: 35px
        }

        .data-table-toggled {
            overflow: hidden
        }

        .data-table-toggled .dataTables_buttons [data-table-action=fullscreen]:before {
            content: '\f16c'
        }

        .flatpickr-calendar {
            border-radius: 2px;
            border: 0;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .11);
            width: auto;
            margin-top: -2px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .flatpickr-months {
            background-color: #39bbb0;
            border-radius: 2px 2px 0 0
        }

        .flatpickr-months .flatpickr-month {
            height: 60px
        }

        .flatpickr-months .flatpickr-next-month, .flatpickr-months .flatpickr-prev-month {
            width: 35px;
            height: 35px;
            padding: 0;
            line-height: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            border-radius: 50%;
            color: #FFF;
            top: 13px;
            transition: background-color .3s
        }

        .colorpicker:after, .colorpicker:before, .trumbowyg-button-pane .trumbowyg-button-group:after, .trumbowyg-button-pane:after {
            display: none
        }

        .flatpickr-months .flatpickr-next-month:hover, .flatpickr-months .flatpickr-prev-month:hover {
            color: #FFF;
            background-color: rgba(255, 255, 255, .2)
        }

        .flatpickr-months .flatpickr-prev-month {
            margin-left: 15px
        }

        .flatpickr-months .flatpickr-next-month {
            margin-right: 15px
        }

        .flatpickr-current-month {
            font-size: 1.25rem;
            color: #FFF;
            padding-top: 18px
        }

        .flatpickr-current-month input.cur-year, .flatpickr-current-month span.cur-month {
            font-weight: 500
        }

        .flatpickr-current-month .numInputWrapper:hover, .flatpickr-current-month span.cur-month:hover {
            background-color: transparent
        }

        .flatpickr-current-month .numInputWrapper span {
            border: 0;
            right: -5px;
            padding: 0
        }

        .flatpickr-current-month .numInputWrapper span:after {
            left: 3px
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #FFF
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #FFF
        }

        span.flatpickr-weekday {
            font-weight: 400;
            color: #333
        }

        .flatpickr-day {
            font-size: .92rem;
            border: 0;
            color: #747a80
        }

        .flatpickr-day.selected, .flatpickr-day.selected:hover {
            background-color: #39bbb0 !important;
            color: #FFF !important
        }

        .flatpickr-day.today, .flatpickr-day.today:hover {
            background-color: #f6f6f6;
            color: #333
        }

        .flatpickr-day:hover {
            background-color: #f6f6f6
        }

        .numInputWrapper span:hover {
            background-color: #FFF
        }

        .flatpickr-time, .flatpickr-time .flatpickr-am-pm:hover, .flatpickr-time .numInputWrapper:hover {
            background-color: #f9f9f9
        }

        .flatpickr-time {
            border: 0 !important
        }

        .flatpickr-innerContainer {
            padding: 15px
        }

        .colorpicker {
            padding: 5px;
            margin-top: 1px
        }

        .colorpicker div {
            border-radius: 2px
        }

        .colorpicker.colorpicker-horizontal {
            width: 160px
        }

        .colorpicker.colorpicker-horizontal .colorpicker-alpha, .colorpicker.colorpicker-horizontal .colorpicker-color, .colorpicker.colorpicker-horizontal .colorpicker-hue {
            width: 100%
        }

        .colorpicker-saturation {
            width: 150px;
            height: 150px
        }

        .colorpicker-saturation i {
            border: 0;
            box-shadow: 0 0 5px rgba(0, 0, 0, .36)
        }

        .colorpicker-saturation i, .colorpicker-saturation i b {
            height: 10px;
            width: 10px
        }

        .colorpicker-alpha, .colorpicker-hue {
            width: 20px;
            height: 150px
        }

        .colorpicker-color, .colorpicker-color div {
            height: 20px
        }

        .color-picker__preview {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            width: 20px;
            height: 20px;
            border-radius: 50%
        }

        .trumbowyg-box, .trumbowyg-editor {
            border-color: #eceff1;
            margin: 0
        }

        .trumbowyg-button-pane {
            background-color: #FFF;
            border-color: #eceff1
        }

        .trumbowyg-button-pane button {
            margin: 0
        }

        .trumbowyg-dropdown {
            border: 0;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .11)
        }

        .trumbowyg-dropdown button {
            font-size: 1rem;
            height: 40px;
            padding: 0 1.5rem
        }

        .trumbowyg-dropdown button svg {
            margin-top: -3px
        }

        .trumbowyg-dropdown button:hover {
            background-color: #f9f9f9
        }

        .trumbowyg-modal-box {
            font-size: 1rem;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .11)
        }

        .trumbowyg-modal-box .trumbowyg-modal-title {
            font-size: 1.2rem;
            color: #333;
            background-color: #FFF;
            font-weight: 500;
            border: 0
        }

        .trumbowyg-modal-box label {
            margin: 15px 20px;
            font-weight: 400
        }

        .trumbowyg-modal-box label .trumbowyg-input-infos span {
            color: #333;
            border-color: #eceff1
        }

        .trumbowyg-modal-box label input {
            border-color: #eceff1;
            font-size: 1rem;
            color: #495057
        }

        .trumbowyg-modal-box label input:focus, .trumbowyg-modal-box label input:hover {
            border-color: #dde2e6
        }

        .trumbowyg-modal-box .trumbowyg-modal-button {
            font-size: 1rem;
            height: auto;
            line-height: 100%;
            border-radius: 2px;
            padding: 7px 0;
            margin: 0 20px;
            bottom: 18px
        }

        .fc-scroller {
            height: auto !important
        }

        .fc th {
            font-weight: 500;
            padding: 12px 12px 10px
        }

        .fc table {
            background: 0 0
        }

        .fc table tr > td:first-child {
            border-left-width: 0
        }

        .fc div.fc-row {
            margin-right: 0;
            border: 0
        }

        .fc button .fc-icon {
            top: -5px
        }

        .fc-unthemed td.fc-today {
            background-color: transparent
        }

        .fc-unthemed td.fc-today span {
            color: #FF9800
        }

        .fc-event {
            padding: 0;
            font-size: .92rem;
            border-radius: 2px;
            border: 0
        }

        .fc-event .fc-title {
            padding: 4px 8px;
            display: block;
            color: #FFF;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 500
        }

        .fc-event .fc-time {
            float: left;
            background: rgba(0, 0, 0, .2);
            padding: 2px 6px;
            margin: 0 0 0 -1px
        }

        .fc-view, .fc-view > table {
            border: 0;
            overflow: hidden
        }

        .fc-view > table > tbody > tr .ui-widget-content {
            border-top: 0
        }

        .fc-icon {
            font-family: Material-Design-Iconic-Font;
            font-size: 1.5rem;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            line-height: 35px;
            transition: background-color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .fc-icon {
                transition: none
            }
        }

        .fc-icon:hover {
            background-color: #f6f6f6
        }

        .fc-button {
            border: 0;
            background: 0 0;
            box-shadow: none
        }

        .calendar {
            z-index: 0
        }

        .calendar td, .calendar th {
            border-color: #f8f9fa
        }

        .calendar .fc-toolbar {
            height: 250px;
            background-color: #FFF;
            border-radius: 2px 2px 0 0;
            position: relative;
            margin-bottom: -2px;
            z-index: 2
        }

        @media (max-width: 575.98px) {
            .calendar .fc-toolbar {
                height: 135px
            }
        }

        .calendar .fc-day-number {
            padding: 6px 10px;
            width: 100%;
            box-sizing: border-box
        }

        @media (min-width: 576px) {
            .dataTables_length {
                min-width: 150px
            }

            .calendar .fc-day-number {
                font-size: 1.5rem;
                color: #8e9499
            }
        }

        .calendar .fc-day-header {
            text-align: left
        }

        .calendar .fc-day-grid-event {
            margin: 1px 9px
        }

        .widget-calendar td, .widget-calendar th {
            border-color: transparent;
            text-align: center
        }

        .widget-calendar .fc-toolbar h2 {
            font-size: 1.2rem;
            padding-top: .3rem
        }

        .widget-calendar .fc-day-number {
            text-align: center;
            width: 100%;
            padding: 0
        }

        .widget-calendar__header {
            background-color: #d066e2;
            border-radius: 2px 2px 0 0;
            padding: 2.2rem 2.1rem
        }

        .widget-calendar__header .actions {
            position: absolute;
            top: 2.5rem;
            right: 1.5rem
        }

        .widget-calendar__year {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, .8);
            margin-bottom: .5rem;
            line-height: 100%
        }

        .widget-calendar__day {
            font-size: 1.5rem;
            line-height: 100%;
            color: #FFF
        }

        .widget-calendar__body {
            padding: 1rem;
            margin-top: 1rem
        }

        .event-tag {
            margin-bottom: 1.5rem
        }

        .event-tag > span {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin: 0 0 3px;
            position: relative;
            display: inline-block;
            vertical-align: top;
            cursor: pointer
        }

        .event-tag > span, .event-tag > span > i {
            transition: all .2s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .event-tag > span, .event-tag > span > i {
                transition: none
            }
        }

        .event-tag > span > input[type=radio] {
            margin: 0;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 2;
            cursor: pointer;
            opacity: 0
        }

        .event-tag > span > input[type=radio]:checked + i {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1)
        }

        .event-tag > span:hover {
            opacity: .8
        }

        .event-tag > span > i {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding: 4px 0 0 7px;
            opacity: 0;
            -webkit-transform: scale(0);
            transform: scale(0)
        }

        .event-tag > span > i:before {
            content: '\f26b';
            font-family: Material-Design-Iconic-Font;
            color: #FFF;
            font-size: 1.2rem;
            z-index: 1
        }

        [data-calendar-month] {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            transition: background-image .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            [data-calendar-month] {
                transition: none
            }
        }

        [data-calendar-month="0"] {
            background-image: url(../img/calendar/january.jpg)
        }

        [data-calendar-month="1"] {
            background-image: url(../img/calendar/february.jpg)
        }

        [data-calendar-month="2"] {
            background-image: url(../img/calendar/march.jpg)
        }

        [data-calendar-month="3"] {
            background-image: url(../img/calendar/april.jpg)
        }

        [data-calendar-month="4"] {
            background-image: url(../img/calendar/may.jpg)
        }

        [data-calendar-month="5"] {
            background-image: url(../img/calendar/june.jpg)
        }

        [data-calendar-month="6"] {
            background-image: url(../img/calendar/july.jpg)
        }

        [data-calendar-month="7"] {
            background-image: url(../img/calendar/august.jpg)
        }

        [data-calendar-month="8"] {
            background-image: url(../img/calendar/september.jpg)
        }

        [data-calendar-month="9"] {
            background-image: url(../img/calendar/october.jpg)
        }

        [data-calendar-month="10"] {
            background-image: url(../img/calendar/november.jpg)
        }

        [data-calendar-month="11"] {
            background-image: url(../img/calendar/december.jpg)
        }

        .swal2-modal {
            border-radius: 2px;
            padding: 2.5rem !important;
            font-family: Roboto, sans-serif;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .11)
        }

        .swal2-modal .swal2-title {
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
            color: #333;
            line-height: inherit;
            margin: 0 0 5px;
            font-weight: 400
        }

        .swal2-modal .swal2-icon, .swal2-modal .swal2-image {
            margin-top: 0;
            margin-bottom: 1.5rem
        }

        .swal2-modal .swal2-content {
            color: #9c9c9c;
            font-size: 1rem;
            font-weight: 400
        }

        .swal2-modal .swal2-actions {
            margin-top: 30px
        }

        .swal2-modal .swal2-actions .btn {
            margin: 0 3px;
            box-shadow: none !important
        }

        .swal2-container.swal2-shown {
            background-color: rgba(0, 0, 0, .2)
        }

        .lg-outer .lg-thumb-item {
            border: 0
        }

        .lg-outer .lg-thumb-item:not(.active) {
            opacity: .25
        }

        .lg-outer .lg-thumb-item:not(.active):hover {
            opacity: .7
        }

        .lg-outer .lg-image {
            border-radius: 2px
        }

        .lg-outer .lg-toogle-thumb {
            border-radius: 50%;
            color: #333;
            height: 51px;
            width: 51px;
            line-height: 41px;
            background-color: #FFF;
            transition: all .5s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .lg-outer .lg-toogle-thumb {
                transition: none
            }
        }

        .lg-outer .lg-toogle-thumb:hover {
            color: #333
        }

        .lg-outer:not(.lg-thumb-open) .lg-toogle-thumb {
            top: -70px
        }

        .lg-outer.lg-thumb-open .lg-toogle-thumb {
            top: -26px
        }

        .lg-thumb.group {
            padding: 20px 0
        }

        .lg-slide em h3 {
            color: #FFF;
            margin-bottom: 5px
        }

        .lg-slide .video-cont {
            box-shadow: 0 2px 5px rgba(0, 0, 0, .16), 0 2px 10px rgba(0, 0, 0, .12)
        }

        .lightbox > a {
            position: relative
        }

        .lightbox > a:after, .lightbox > a:before {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            transition: all .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .lightbox > a:after, .lightbox > a:before {
                transition: none
            }
        }

        .lightbox > a:before {
            content: '\f1ee';
            font-family: Material-Design-Iconic-Font;
            font-size: 2.3rem;
            color: #FFF;
            bottom: 0;
            right: 0;
            margin: auto;
            width: 25px;
            height: 25px;
            line-height: 25px;
            z-index: 2;
            -webkit-transform: scale(2);
            transform: scale(2)
        }

        .lightbox > a:after {
            content: '';
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, .3);
            z-index: 1
        }

        .lightbox > a:hover:after, .lightbox > a:hover:before {
            opacity: 1
        }

        .lightbox > a:hover:before {
            -webkit-transform: scale(1);
            transform: scale(1)
        }

        .scrollbar-inner {
            height: 100%;
            overflow: auto
        }

        .scrollbar-inner > .scroll-element {
            transition: opacity .3s;
            margin-right: 2px
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .scrollbar-inner > .scroll-element {
                transition: none
            }
        }

        .scrollbar-inner > .scroll-element.scroll-y {
            width: 3px;
            right: 0
        }

        .scrollbar-inner > .scroll-element.scroll-x {
            height: 3px;
            bottom: 0
        }

        .scrollbar-inner > .scroll-element .scroll-bar, .scrollbar-inner > .scroll-element .scroll-element_track {
            transition: background-color .3s
        }

        @media screen and (prefers-reduced-motion: reduce) {
            .scrollbar-inner > .scroll-element .scroll-bar, .scrollbar-inner > .scroll-element .scroll-element_track {
                transition: none
            }
        }

        .scrollbar-inner > .scroll-element .scroll-element_track {
            background-color: transparent
        }

        .scrollbar-inner:not(:hover) .scroll-element {
            opacity: 0
        }

        .waves-effect {
            position: relative;
            overflow: hidden;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .waves-effect .waves-ripple {
            position: absolute;
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-top: -50px;
            margin-left: -50px;
            opacity: 0;
            transition: all .5s ease-out;
            transition-property: opacity, -webkit-transform;
            transition-property: transform, opacity;
            transition-property: transform, opacity, -webkit-transform;
            -webkit-transform: scale(0) translate(0, 0);
            transform: scale(0) translate(0, 0);
            pointer-events: none
        }

        .waves-effect.btn-link .waves-ripple, .waves-effect.btn-secondary .waves-ripple, .waves-effect:not(.waves-light) .waves-ripple {
            background: rgba(0, 0, 0, .08)
        }

        .waves-effect.btn:not(.btn-secondary):not(.btn-link) .waves-ripple, .waves-effect.waves-light .waves-ripple {
            background: rgba(255, 255, 255, .5)
        }

        .waves-effect.waves-classic .waves-ripple {
            background: rgba(0, 0, 0, .08)
        }

        .waves-effect.waves-classic.waves-light .waves-ripple {
            background: rgba(255, 255, 255, .5)
        }

        .waves-notransition {
            transition: none !important
        }

        .waves-button, .waves-circle {
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-mask-image: -webkit-radial-gradient(circle, #FFF 100%, #000 100%)
        }

        .waves-input-wrapper .waves-button-input {
            position: relative;
            top: 0;
            left: 0;
            z-index: 1;
            border: 0
        }

        .waves-block {
            display: block
        }

        ul.jqtree-tree {
            border-top: 1px solid #f1f4f7
        }

        ul.jqtree-tree li.jqtree-selected > .jqtree-element, ul.jqtree-tree li.jqtree-selected > .jqtree-element:hover {
            background: #f9f9f9;
            text-shadow: none
        }

        ul.jqtree-tree li:not(.jqtree-selected) > .jqtree-element:hover {
            background: #fcfcfc
        }

        ul.jqtree-tree li.jqtree-folder {
            margin-bottom: 0
        }

        ul.jqtree-tree li.jqtree-folder:not(.jqtree-closed) + li.jqtree_common {
            position: relative
        }

        ul.jqtree-tree li.jqtree-folder:not(.jqtree-closed) + li.jqtree_common:before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            width: 100%;
            background-color: #f1f4f7;
            height: 1px
        }

        ul.jqtree-tree li.jqtree-folder.jqtree-closed {
            margin: 0
        }

        ul.jqtree-tree li.jqtree-ghost span.jqtree-line {
            background-color: #2196F3
        }

        ul.jqtree-tree li.jqtree-ghost span.jqtree-circle {
            border-color: #2196F3
        }

        ul.jqtree-tree .jqtree-moving > .jqtree-element .jqtree-title {
            outline: 0
        }

        ul.jqtree-tree span.jqtree-border {
            border-radius: 0;
            border-color: #2196F3
        }

        ul.jqtree-tree .jqtree-toggler {
            position: absolute;
            height: 18px;
            width: 18px;
            background: #FFF;
            border: 1px solid #e1e7ee;
            color: #333;
            border-radius: 50%;
            padding: 0 0 0 1px;
            top: 12px;
            left: -10px;
            line-height: 17px;
            font-size: 1rem;
            text-align: center
        }

        ul.jqtree-tree .jqtree-element {
            position: relative;
            padding: 10px 20px;
            border: 1px solid #f1f4f7;
            border-top: 0;
            margin-bottom: 0
        }

        ul.jqtree-tree .jqtree-title {
            color: #333;
            margin-left: 0
        }

        ul.jqtree-tree ul.jqtree_common {
            margin-left: 22px;
            padding-left: 10px
        }

        .jq-ry-container {
            padding: 0;
            display: inline-block
        }

        .text-count-wrapper {
            position: absolute;
            bottom: -23px;
            height: 20px;
            width: 100%;
            left: 0;
            font-size: .875rem
        }

        .error-text-min {
            float: right
        }

    </style>
</head>

<body class="bg-light">

<div class="printPage">
    <?php if(isset($ordem_compra['prioridade']) && $ordem_compra['prioridade'] == 1){ ?>
        <div class="row">
            <div class="col-12 bg-warning text-center">
                <p>ORDEM DE COMPRA URGENTE!</p>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-6 text-center">
            <h5><b>Ordem de Compra:</b> <?php echo $ordem_compra['Cd_Ordem_Compra']; ?></h5>
            <h5><b>Situação: </b><?php echo $ordem_compra['situacao']; ?></h5>
            <h5><b>Usuário Pharmanexo: </b><?php echo $this->session->nome; ?></h5>
        </div>
        <div class="col-6 text-center">
            <h6><?php echo $ordem_compra['comprador']['nome_fantasia']; ?></h6>
            <h6><b>Razão Social: </b><?php echo $ordem_compra['comprador']['razao_social']; ?></h6>
            <h6><b>Cotação: </b><?php echo $ordem_compra['Cd_Cotacao']; ?></h6>
            <h6><b>Pedido ERP: </b><?php echo $ordem_compra['transaction_id']; ?></h6>
            <h6>
                <b>Requisitante: </b> <?php echo $ordem_compra['Nm_Aprovador']; ?>

            </h6>
        </div>
    </div>

    <div class="card border-secondary mt-3">
        <div class="card-header border-secondary">
            <p class="card-title">Dados do Faturamento</p>
        </div>
        <div class="card-body border-secondary p-1">

            <div class="d-flex">
                <div class="p-2"><b>Empresa: </b><?php echo $ordem_compra['comprador']['razao_social']; ?></div>
            </div>
            <div class="d-flex">
                <div class="p-2"><b>CNPJ: <?php echo $ordem_compra['comprador']['cnpj']; ?></b></div>
            </div>
            <div class="d-flex">
                <div class="p-2"><b>E-mail: </b><?php echo $ordem_compra['comprador']['email']; ?></div>
            </div>
            <div class="d-flex justify-content-between">
                <div class="p-2">
                    <b>Data Entrega: </b> <?php echo date("d/m/Y", strtotime($ordem_compra['Dt_Previsao_Entrega'])); ?>
                </div>
                <div class="p-2"><b>Cond. Pagto: </b><?php echo $ordem_compra['form_pagamento']; ?>
                </div>
                <div class="p-2"><b>Tipo Frete: </b>CIF</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card border-secondary">
                <div class="card-header border-secondary">
                    <p class="card-title">Endereço de Entrega</p>
                </div>
                <div class="card-body p-1">

                    <div class="d-flex">
                        <div class="p-2">
                            <small>
                                <?php if (isset($ordem_compra['Nm_Logradouro']) && !empty($ordem_compra['Nm_Logradouro'])) echo $ordem_compra['Nm_Logradouro'] . ' - ' ?>

                                <?php if (isset($ordem_compra['Ds_Complemento_Logradouro']) && !empty($ordem_compra['Ds_Complemento_Logradouro'])) echo $ordem_compra['Ds_Complemento_Logradouro'] . ' - ' ?>

                                <?php if (isset($ordem_compra['Nm_Bairro']) && !empty($ordem_compra['Nm_Bairro'])) echo $ordem_compra['Nm_Bairro'] . ' - ' ?>

                                <?php if (isset($ordem_compra['Nm_Cidade']) && !empty($ordem_compra['Nm_Cidade'])) echo $ordem_compra['Nm_Cidade'] . ' - ' ?>

                                <?php if (isset($ordem_compra['Id_Unidade_Federativa']) && !empty($ordem_compra['Id_Unidade_Federativa'])) echo $ordem_compra['Id_Unidade_Federativa'] ?>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2">
                            <b>CEP: </b><?php if (isset($ordem_compra['Nr_Cep']) && !empty($ordem_compra['Nr_Cep'])) echo $ordem_compra['Nr_Cep']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-secondary">
                <div class="card-header border-secondary">
                    <p class="card-title">Observação</p>
                </div>
                <div class="card-body border-secondary p-1">
                    <small><?php echo $ordem_compra['Ds_Observacao']; ?></small>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card border-secondary">
                <div class="card-header border-secondary">
                    <p class="card-title">Dados do Fornecedor</p>
                </div>
                <div class="card-body  p-1">
                    <div class="d-flex">
                        <div class="p-2"><?php echo $fornecedor['nome_fantasia']; ?></div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2"><b>Razão Social: </b><?php echo $fornecedor['razao_social']; ?></div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2"><b>CNPJ: </b><?php echo $fornecedor['cnpj']; ?></div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2"><b>End.: </b> <small>
                                <?php if (isset($fornecedor['endereco']) && !empty($fornecedor['endereco'])) echo $fornecedor['endereco'] . ' - '; ?><?php if (isset($fornecedor['numero']) && !empty($fornecedor['numero'])) echo $fornecedor['numero'] . ' - '; ?><?php if (isset($fornecedor['bairro']) && !empty($fornecedor['bairro'])) echo $fornecedor['bairro'] . ' - '; ?><?php if (isset($fornecedor['cidade']) && !empty($fornecedor['cidade'])) echo $fornecedor['cidade'] . ' - '; ?><?php if (isset($fornecedor['estado']) && !empty($fornecedor['estado'])) echo $fornecedor['estado']; ?>
                            </small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2">
                            <b>CEP: </b><?php if (isset($fornecedor['cep'])) echo $fornecedor['cep']; ?></div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2">
                            <b>Fone: </b><?php if (isset($fornecedor['telefone'])) echo $fornecedor['telefone']; ?>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2">
                            <b>Prazo de Entrega ofertada: </b><?php echo isset($ordem_compra['oferta']['prazo_entrega']) ? $ordem_compra['oferta']['prazo_entrega'] ." dias" : 'Não informado'; ?>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="p-2">
                            <b>Valor do Faturamento Mínimo: <?php echo isset( $ordem_compra['oferta']['valor_minimo'] ) ? $ordem_compra['oferta']['valor_minimo'] : 'Não informado' ; ?></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card ">
        <div class="card-header ">
            <p class="card-title">Produtos da Ordem de Compra</p>
        </div>
        <div class="card-body p-1">
            <div class="table-responsive col-sm">
                <table class="table table-sm table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Produto Catálogo</th>
                        <th>Produto</th>
                        <th>Cód. Fornecedor</th>
                        <th>Marca</th>
                        <th>Embalagem</th>
                        <th>Qtd.</th>
                        <th>Preço (R$)</th>
                        <th>Total (R$)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($ordem_compra['produtos'] as $oc): ?>
                        <tr>
                            <td><?php echo (isset($oc['produto_catalogo'])) ? $oc['produto_catalogo'] : ''; ?></td>
                            <td><?php echo $oc['Ds_Produto_Comprador']; ?></td>
                            <td><?php echo $oc['codigo']; ?></td>
                            <td><?php echo $oc['Ds_Marca']; ?></td>
                            <td><?php echo $oc['Ds_Unidade_Compra']; ?></td>
                            <td><?php echo $oc['Qt_Produto']; ?></td>
                            <td><?php echo number_format($oc['Vl_Preco_Produto'], 4, ',', '.'); ?></td>
                            <td><?php echo number_format($oc['Vl_Preco_Produto'] * $oc['Qt_Produto'], 4, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

    <div class="card">
        <div class="card-body">
            <h4 class="text-right"><?php if (isset($ordem_compra['total'])) echo "Valor total: R$ " . number_format($ordem_compra['total'], 4, ',', '.')?></h4>
        </div>
    </div>
</div>

</body>
</html>