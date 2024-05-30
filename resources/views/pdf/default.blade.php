<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Default Layout</title>

    <style>
        body {
            font-family: Helvetica, Arial, sans;
            color: #494949;
            font-size: 12px;
            /*background-color: rgb(234,234,234);*/
            margin: 0px;
            height: 100%
        }

        h1 {
            font-size: 30px;
            font-weight: 800;
        }

        h2 {
            font-size: 23px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        p {
            line-height: 18px;
            padding: 0;
            margin: 0;
        }

        /*Table*/
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr th, table tbody tr td, table tfoot tr td {
            padding: 10px;
        }

        table.header-table tbody tr td {
            padding-top: 2px;
            padding-bottom: 2px;
            padding-left: 0;
            padding-right: 0;
        }

        table tbody tr td {
            /*border-top: 1px solid #c0c0c0;*/
        }

        /*Login*/
        .logo {
            text-align: left;
        }

        /*wrapping*/
        .wrapping-1 {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            background-color: #fff;
            background-repeat: no-repeat;
            background-position: -280px -50px;
        }

        .header, .footer, .wrapping-divider {
            display: flow-root;
            clear: both;
            width: 100%;
        }

        .wrapping-body {
            padding: 10px 20px 40px 20px;
        }

        .body-text {
            font-weight: bold;
            font-size: 17px;
        }

        .wrapping-footer {
            padding: 20px 30px 20px 20px;
            font-size: 13px;
        }

        .wrapping-footer p a {
            color: #adadad;
        }

        .no-underline {
            text-decoration: none;
        }

        .border-top-gray {
            border-top: 1px solid #c0c0c0;
        }

        .border-bottom-black {
            border-bottom: 1px solid #151515;
        }

        /* Margin Top 0-30 */
        .no-margin {
            margin: 0;
        }

        .m-t-0 {
            margin-top: 0px;
        }

        .m-b-0 {
            margin-bottom: 0px;
        }

        .m-b-3 {
            margin-bottom: 3px;
        }

        .m-t-3 {
            margin-top: 3px;
        }

        .m-b-5 {
            margin-bottom: 5px;
        }

        .m-t-5 {
            margin-top: 5px;
        }

        .m-r-5 {
            margin-right: 5px;
        }

        .m-t-10 {
            margin-top: 10px;
        }

        .m-t-15 {
            margin-top: 15px;
        }

        .m-t-20 {
            margin-top: 20px;
        }

        .m-t-25 {
            margin-top: 25px;
        }

        .m-t-30 {
            margin-top: 30px;
        }

        .m-t-35 {
            margin-top: 35px;
        }

        .m-t-40 {
            margin-top: 40px;
        }

        .m-t-45 {
            margin-top: 45px;
        }

        .m-t-50 {
            margin-top: 50px;
        }

        .m-t-60 {
            margin-top: 60px;
        }

        .m-t-70 {
            margin-top: 70px;
        }

        .m-t-80 {
            margin-top: 80px;
        }

        .m-b-35 {
            margin-bottom: 35px;
        }

        .m-b-40 {
            margin-bottom: 40px;
        }

        .m-b-45 {
            margin-bottom: 45px;
        }

        .m-b-50 {
            margin-bottom: 50px;
        }

        .m-b-60 {
            margin-bottom: 50px;
        }

        .m-l-0 {
            margin-left: 0px;
        }

        .m-l-5 {
            margin-left: 5px;
        }

        .m-l-10 {
            margin-left: 10px;
        }

        .m-l-15 {
            margin-left: 15px;
        }

        .m-l-20 {
            margin-left: 20px;
        }

        .m-l-25 {
            margin-left: 25px;
        }

        .m-l-30 {
            margin-left: 30px;
        }

        /* Padding Top 0-30 */
        .no-padding {
            padding: 0;
        }

        .p-t-0 {
            padding-top: 0px;
        }

        .p-t-5 {
            padding-top: 5px;
        }

        .p-t-10 {
            padding-top: 10px;
        }

        .p-t-15 {
            padding-top: 15px;
        }

        .p-t-20 {
            padding-top: 20px;
        }

        .p-t-25 {
            padding-top: 25px;
        }

        .p-t-30 {
            padding-top: 30px;
        }

        .p-l-0 {
            padding-left: 0px;
        }

        .p-l-5 {
            padding-left: 5px;
        }

        .p-l-10 {
            padding-left: 10px;
        }

        .p-l-15 {
            padding-left: 15px;
        }

        .p-l-20 {
            padding-left: 20px;
        }

        .p-l-30 {
            padding-left: 30px;
        }

        .p-l-40 {
            padding-left: 40px;
        }

        .p-l-50 {
            padding-left: 50px;
        }

        .padding-10 {
            padding: 10px;
        }

        /* with Size 20%-100%*/
        .w-5 {
            width: 5%;
        }

        .w-10 {
            width: 10%;
        }

        .w-20 {
            width: 20%;
        }

        .w-22 {
            width: 22%;
        }

        .w-25 {
            width: 25%;
        }

        .w-30 {
            width: 30%;
        }

        .w-35 {
            width: 30%;
        }

        .w-40 {
            width: 40%;
        }

        .w-50 {
            width: 50%;
        }

        .w-60 {
            width: 60%;
        }

        .w-70 {
            width: 70%;
        }

        .w-75 {
            width: 75%;
        }

        .w-80 {
            width: 80%;
        }

        .w-100 {
            width: 100%;
        }

        .tbl-600 {
            width: 600px;
        }

        /*text-align*/
        .tx-left {
            text-align: left;
        }

        .tx-right {
            text-align: right;
        }

        .tx-center {
            text-align: center;
        }

        /*vertical-align*/
        .txv-top {
            vertical-align: top;
        }

        .txv-center {
            vertical-align: middle;
        }

        .txv-bottom {
            vertical-align: bottom;
        }

        /*color text*/
        .color-gray {
            color: #8b8b8b;
        }

        .color-blue {
            color: #0275d8;
        }

        .color-black {
            color: #000;
        }

        /*font size*/
        .fs-10 {
            font-size: 10px;
        }

        .fs-12 {
            font-size: 12px;
        }

        .fs-13 {
            font-size: 13px;
        }

        .fs-14 {
            font-size: 14px;
        }

        .fs-15 {
            font-size: 15px;
        }

        .fs-16 {
            font-size: 16px;
        }

        .fs-17 {
            font-size: 17px;
        }

        .fs-18 {
            font-size: 18px;
        }

        .fs-19 {
            font-size: 19px;
        }

        /*font weight*/
        .fw-100 {
            font-weight: 100;
        }

        .fw-200 {
            font-weight: 200;
        }

        .fw-300 {
            font-weight: 300;
        }

        .fw-400 {
            font-weight: 400;
        }

        .fw-500 {
            font-weight: 500;
        }

        .fw-600 {
            font-weight: 600;
        }

        .fw-700 {
            font-weight: 700;
        }

        .fw-800 {
            font-weight: 800;
        }

        .fw-900 {
            font-weight: 900;
        }

        .fw-bold {
            font-weight: bold;
        }

        .pull-right {
            float: right;
        }

        .pull-left {
            float: left;
        }

        .bg-blue {
            background-color: #0275d8;
        }

        .bg-yellow {
            background-color: #ffc800;
        }

        .bg-black {
            background-color: black;
        }

        .bg-grey {
            background-color: #8b8b8b;
        }

        .bg-grey-light {
            background-color: #ddd;
        }

        .no-border {
            /*border: unset;*/
        }

        .color-white {
            color: white;
        }

        .btn {
            display: inline-block;
            font-weight: normal;
            line-height: 1.25;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: 6px 10px;
            font-size: 1rem;
            -webkit-transition: all 0.2s ease-in-out;
            -o-transition: all 0.2s ease-in-out;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }

        .btn-primary {
            color: #212121;
            background-color: #ffc800;
            border-color: #ffc800;
        }

        .page-break { /*untuk pembatas perhalaman*/
            page-break-after: always;
        }

        .inline-block {
            display: inline-block;
        }

        .inline-flex {
            display: inline-flex;
        }

        .clear-both {
            clear: both;
        }

        .row {
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: center;
            justify-content: center;
        }

        .row > div {
            -webkit-box-flex: 1;
            -webkit-flex: 1;
            flex: 1;
        }

        .row > div:last-child {
            margin-right: 0;
        }

    </style>

</head>
<body style="">

<div class="wrapping-1">
    <div class="wrapping-body">
        <div class="header m-b-40">
            <div class="logo pull-left w-30">
                <img src="https://storage.globalxtreme-gateway.net/link/share/images/logo/globalxtreme/gx_new_logo.png" alt="" height="auto"
                     width="200px">
            </div>
            <div class="pull-right w-40" style="width: 350px">
                <h1 class="no-margin">PDF Default Layout</h1>
            </div>
        </div>
        <br>
    </div>

    <div class="wrapping-footer m-t-20" style="">
        <div class="wrapping-divider">
            <div class="row w-100">
                <div class="w-50"></div>
                <div class="w-50">
                    <div class="pull-right w-40">
                        <p class="tx-right fw-bold">Issued By,</p>
                        <div class="tx-right m-t-10 border-bottom-black" style="height: 60px"></div>
                        <p class="tx-right">GlobalXtreme</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-break"></div>

</body>
</html>
