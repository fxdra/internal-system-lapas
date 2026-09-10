@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        /* HEADER DATA WBP KLINIK */
        .klinik-header {
            padding-bottom: 4px;
        }

        .klinik-header h4 {
            color: #283144;
            font-size: 20px;
            letter-spacing: -0.2px;
        }

        .klinik-header small {
            font-size: 13px;
        }

        .klinik-total-badge {
            display: inline-flex;
            align-items: center;

            padding: 5px 10px;

            border-radius: 8px;

            background: rgba(52, 84, 209, 0.10);
            color: #3454d1;

            font-size: 12px;
            font-weight: 600;
        }

        /* STAT CARD */
        .klinik-stat-card {
            height: 100%;

            padding: 20px;

            background: #ffffff;

            border: 1px solid #e9ecef;
            border-radius: 14px;

            transition: all .2s ease;
        }

        .klinik-stat-card:hover {
            transform: translateY(-2px);

            box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        }

        .klinik-stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .klinik-stat-title {
            color: #6c757d;

            font-size: 13px;
            font-weight: 500;

            margin-bottom: 8px;
        }

        .klinik-stat-value {
            color: #283144;

            font-size: 28px;
            font-weight: 700;

            line-height: 1.2;
        }

        .klinik-stat-description {
            color: #8a94a6;

            font-size: 12px;

            margin-top: 8px;
        }

        .klinik-stat-primary .klinik-stat-value {
            color: #3454d1;
        }

        .klinik-stat-success .klinik-stat-value {
            color: #198754;
        }

        .klinik-stat-warning .klinik-stat-value {
            color: #d99a00;
        }

        /* SEARCH */
        .klinik-search {
            width: 100%;
            margin-bottom: 16px;
        }

        .klinik-search .form-control {
            width: 100%;
            height: 46px;

            border: 1px solid #d9dee7;
            border-radius: 10px;

            padding: 0 15px;

            font-size: 13px;

            background-color: #fff;
        }

        .klinik-search .form-control:focus {
            border-color: #3454d1;

            box-shadow: 0 0 0 3px rgba(52, 84, 209, 0.08);

            outline: none;
        }

        .klinik-search .form-control::placeholder {
            color: #98a2b3;
        }

        .klinik-search .btn-primary {
            min-width: 80px;

            border-radius: 0 10px 10px 0;

            font-size: 13px;
            font-weight: 600;
        }

        .klinik-search .btn-outline-secondary {
            margin-left: 6px;

            border-radius: 10px;
        }

        .klinik-filter-info {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-top: 12px;
            margin-bottom: 16px;

            padding: 10px 14px;

            border: 1px solid #e5e7eb;
            border-radius: 8px;

            background: #f8f9fa;

            font-size: 12px;
            color: #667085;

            gap: 10px;
        }

        .klinik-filter-info>div {
            flex: 1 1 auto;
            min-width: 0;
        }

        /* BUTTON KEMBALI */
        .klinik-btn-kembali {
            flex: 0 0 auto;

            min-width: 120px;
            height: 34px;

            padding: 5px 10px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 3px;

            border-radius: 6px;

            font-size: 10px !important;
            font-weight: 600;

            line-height: 1.15;

            white-space: nowrap;
        }

        .klinik-btn-kembali i {
            font-size: 10px;
        }

        /* TABLE */
        .klinik-table-card {
            background: #ffffff;

            border: 1px solid #e9ecef;
            border-radius: 14px;

            overflow: hidden;

            box-shadow: 0 4px 18px rgba(0, 0, 0, .035);
        }

        .klinik-table-card .card-body {
            padding: 0;
        }

        .klinik-table {
            margin-bottom: 0;
            width: 100%;
            table-layout: fixed;
        }

        /* COLUMN WIDTH */
        .klinik-table .col-rekam-medis {
            width: 14%;
        }

        .klinik-table .col-nik {
            width: 17%;
        }

        .klinik-table .col-nama {
            width: 41%;
        }

        .klinik-table .col-tanggal-masuk {
            width: 20%;
        }

        .klinik-table .col-aksi {
            width: 8%;
        }

        .klinik-table thead th {
            background: #f8f9fb;

            color: #6c757d;

            border-bottom: 1px solid #e9ecef;

            padding: 14px 16px;

            font-size: 11px;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: .4px;

            white-space: nowrap;
        }

        .klinik-table tbody td {
            padding: 15px 16px;

            color: #283144;

            border-bottom: 1px solid #f0f1f3;

            font-size: 13px;

            vertical-align: middle;
        }

        /* NAMA WBP */
        .klinik-wbp-name {
            color: #283144;

            font-size: 13px;
            font-weight: 650;

            line-height: 1.4;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .klinik-wbp-reg {
            display: block;

            color: #8a94a6;

            font-size: 11px;

            margin-top: 3px;
        }

        /* NO. REKAM MEDIS */
        .klinik-rekam-medis {
            display: inline-flex;
            align-items: center;

            padding: 6px 9px;

            background: #f5f7fa;

            border: 1px solid #e8ebef;

            border-radius: 7px;

            color: #3f4857;

            font-family: monospace;

            font-size: 12px;
            font-weight: 600;

            letter-spacing: .2px;
        }

        .klinik-rekam-kosong {
            color: #a0a7b1;
            font-size: 12px;
        }

        /* NIK */
        .klinik-nik {
            display: block;

            color: #3f4857;

            font-size: 12px;
            font-weight: 500;

            white-space: nowrap;
        }

        .klinik-nik-kosong {
            color: #a0a7b1;

            font-size: 12px;

            white-space: nowrap;
        }

        /* STATUS */
        .klinik-status {
            display: inline-flex;
            align-items: center;

            padding: 6px 10px;

            border-radius: 7px;

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }

        .klinik-status-lengkap {
            background: rgba(25, 135, 84, .10);

            color: #198754;
        }

        .klinik-status-belum {
            background: rgba(240, 173, 0, .12);

            color: #a66f00;
        }


        /* ACTION */
        .klinik-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 34px;
            height: 34px;

            border-radius: 8px;

            font-size: 13px;

            transition: all .15s ease;
        }

        .klinik-action:hover {
            transform: translateY(-1px);
        }

        .klinik-action-detail {
            color: #3454d1;

            background: rgba(52, 84, 209, .10);

            border: 1px solid rgba(52, 84, 209, .12);
        }

        .klinik-action-detail:hover {
            color: #3454d1;

            background: rgba(52, 84, 209, .16);
        }

        .klinik-action-edit {
            color: #6c757d;

            background: #f5f6f8;

            border: 1px solid #e7e9ec;
        }

        .klinik-action-edit:hover {
            color: #495057;

            background: #eceef1;
        }


        /* EMPTY STATE */
        .klinik-empty {
            padding: 60px 20px !important;

            text-align: center;

            color: #6c757d;
        }


        /* PAGINATION */
        .klinik-pagination {
            padding: 16px 20px;

            border-top: 1px solid #f0f1f3;
        }

        .klinik-pagination-info {
            color: #8a94a6;

            font-size: 12px;
        }

        .klinik-pagination-info strong {
            color: #596273;
        }


        /* RESPONSIVE */
        @media (max-width: 767.98px) {

            .klinik-stat-value {
                font-size: 24px;
            }

            .klinik-table thead th,
            .klinik-table tbody td {
                padding: 12px;
            }

            .klinik-table-card {
                border-radius: 10px;
            }

            .klinik-pagination {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }

        /* MODAL EDIT WBP KLINIK */
        .klinik-modal-overlay {
            position: fixed;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(0, 0, 0, .45);

            opacity: 0;
            visibility: hidden;

            transition: .2s ease;

            z-index: 99999;
        }

        .klinik-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .klinik-modal {
            width: 100%;
            max-width: 650px;

            background: #fff;

            border-radius: 16px;

            overflow: hidden;

            box-shadow: 0 15px 40px rgba(0, 0, 0, .2);

            transform: translateY(-15px);
            transition: .2s ease;
        }

        .klinik-modal-overlay.show .klinik-modal {
            transform: translateY(0);
        }


        /* HEADER */
        .klinik-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 18px 22px;

            background: #f8f9fa;

            border-bottom: 1px solid #e9ecef;
        }

        .klinik-modal-header h5 {
            margin: 0;

            font-size: 18px;
            font-weight: 700;

            color: #283144;
        }

        .klinik-modal-header small {
            color: #8a94a6;
        }


        /* CLOSE */
        .klinik-modal-close {
            width: 36px;
            height: 36px;

            border: none;
            border-radius: 8px;

            background: transparent;

            font-size: 28px;
            line-height: 1;

            color: #6c757d;

            cursor: pointer;

            transition: .2s;
        }

        .klinik-modal-close:hover {
            background: #e9ecef;
            color: #dc3545;
        }


        /* BODY */
        .klinik-modal-body {
            padding: 24px;
        }


        /* IDENTITY */
        .klinik-modal-identity {
            display: grid;
            grid-template-columns: 1fr 1fr;

            gap: 12px;

            margin-bottom: 24px;
        }

        .klinik-modal-info {
            padding: 14px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;

            border-radius: 10px;
        }

        .klinik-modal-info span {
            display: block;

            margin-bottom: 5px;

            font-size: 11px;

            color: #8a94a6;
        }

        .klinik-modal-info strong {
            display: block;

            font-size: 13px;

            color: #283144;
        }


        /* FORM */
        .klinik-form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 13px;
            font-weight: 600;

            color: #343a40;
        }

        .klinik-form-group input {
            height: 45px;

            border-radius: 9px;
        }

        .klinik-form-group small {
            display: block;

            margin-top: 6px;

            font-size: 12px;

            color: #8a94a6;
        }

        .klinik-form-error {
            margin-top: 7px;

            font-size: 12px;

            color: #dc3545;
        }


        /* FOOTER */
        .klinik-modal-footer {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding: 16px 22px;

            border-top: 1px solid #e9ecef;
        }

        .klinik-modal-footer .btn {
            min-width: 100px;

            height: 40px;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;
        }


        /* MOBILE - TABLE DATA WBP KLINIK */
        @media (max-width: 600px) {

            #klinikTableWrapper {
                width: 100%;
                max-width: 100%;
                overflow: hidden;
            }

            .klinik-table-card {
                width: 100%;
                max-width: 100%;
                border-radius: 10px;
                overflow: hidden;
            }

            .klinik-table-card .card-body {
                padding: 0;
            }

            .klinik-table-card .table-responsive {
                width: 100%;
                max-width: 100%;
                overflow: hidden;
            }

            /* TABLE */
            .klinik-table {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;

                margin: 0 !important;

                table-layout: fixed !important;
                border-collapse: collapse;
            }


            /* CELL DASAR */
            .klinik-table th,
            .klinik-table td {
                box-sizing: border-box;

                padding: 8px 4px !important;

                vertical-align: middle;

                white-space: normal !important;

                overflow: hidden;
            }

            /* LEBAR KOLOM */
            .klinik-table .col-rekam-medis,
            .klinik-table td:nth-child(1) {
                width: 17% !important;
            }

            .klinik-table .col-nik,
            .klinik-table td:nth-child(2) {
                width: 18% !important;
            }

            .klinik-table .col-nama,
            .klinik-table td:nth-child(3) {
                width: 30% !important;
            }

            .klinik-table .col-tanggal-masuk,
            .klinik-table td:nth-child(4) {
                width: 15% !important;
            }

            .klinik-table .col-aksi,
            .klinik-table td:nth-child(5) {
                width: 20% !important;
            }

            /* HEADER */
            .klinik-table thead th {
                padding: 8px 4px !important;

                font-size: 7px !important;
                line-height: 1.15 !important;

                font-weight: 700;

                letter-spacing: 0;

                white-space: normal !important;
                word-break: normal !important;
                overflow-wrap: normal !important;

                text-transform: uppercase;
            }

            .klinik-table thead .col-aksi {
                text-align: center;
            }

            /* NO REKAM MEDIS */
            .klinik-table .klinik-rekam-medis,
            .klinik-table .klinik-rekam-kosong {
                display: block;

                max-width: 100%;

                padding: 4px 5px;

                font-size: 7px;
                line-height: 1.25;

                white-space: normal;

                overflow-wrap: anywhere;
                word-break: break-all;
            }

            .klinik-table .klinik-rekam-medis {
                border-radius: 6px;
            }

            /* NIK */
            .klinik-table .klinik-nik,
            .klinik-table .klinik-nik-kosong {
                display: block;

                max-width: 100%;

                font-size: 7px;
                line-height: 1.25;

                white-space: normal;

                overflow-wrap: anywhere;
                word-break: break-all;
            }

            /* NAMA WBP */
            .klinik-table .klinik-wbp-name {
                display: block;

                width: 100%;
                min-width: 0 !important;
                max-width: 100%;

                font-size: 8px;
                font-weight: 650;

                line-height: 1.25;

                white-space: normal !important;

                overflow: hidden;
                text-overflow: clip;

                overflow-wrap: anywhere;
                word-break: normal;
            }

            .klinik-table .klinik-wbp-reg {
                display: block;

                width: 100%;
                max-width: 100%;

                margin-top: 3px;

                font-size: 6.5px;
                line-height: 1.2;

                color: #8a94a6;

                white-space: normal;

                overflow-wrap: anywhere;
                word-break: break-word;
            }

            /* TANGGAL */
            .klinik-table .klinik-tanggal-masuk,
            .klinik-table .klinik-tanggal-kosong {
                display: block;

                width: 100%;
                max-width: 100%;

                font-size: 7px;
                line-height: 1.25;

                white-space: normal;

                overflow-wrap: anywhere;
                word-break: normal;
            }

            /* AKSI */
            .klinik-table td:nth-child(5) {
                padding-left: 2px !important;
                padding-right: 2px !important;

                text-align: center;
                white-space: nowrap !important;
            }

            .klinik-table td:nth-child(5) .d-flex {
                display: flex !important;

                width: 100%;

                justify-content: center;
                align-items: center;

                gap: 3px !important;

                flex-wrap: nowrap !important;
            }

            .klinik-table .klinik-action {
                width: 25px;
                height: 25px;

                min-width: 25px;
                max-width: 25px;

                min-height: 25px;
                max-height: 25px;

                padding: 0 !important;

                flex: 0 0 25px;

                display: inline-flex;

                align-items: center;
                justify-content: center;

                border-radius: 6px;
            }

            .klinik-table .klinik-action i {
                font-size: 11px;

                line-height: 1;
            }

            /* EMPTY STATE */
            .klinik-table .klinik-empty {
                padding: 30px 12px !important;

                text-align: center;
            }

            .klinik-table .klinik-empty-title {
                font-size: 11px;
            }

            .klinik-table .klinik-empty-description {
                font-size: 9px;
                line-height: 1.4;
            }

            /* FILTER INFO */
            .klinik-filter-info {
                gap: 7px;
                padding: 8px 10px;
                font-size: 10px;
                line-height: 1.45;
            }

            .klinik-filter-info>div {
                flex: 1 1 auto;
                min-width: 0;
            }

            .klinik-btn-kembali {
                flex: 0 0 120px;

                width: 120px;
                min-width: 120px;
                max-width: 120px;

                height: 32px;
                min-height: 32px;

                padding: 4px 7px;

                border-radius: 5px;

                font-size: 8.5px !important;
                line-height: 1.1;

                white-space: normal;
                text-align: center;
            }

            .klinik-btn-kembali i {
                font-size: 9px;
                margin-right: 1px !important;
            }
        }

        /* MOBILE - MODAL EDIT */
        @media (max-width: 600px) {

            .klinik-modal-overlay {
                padding: 10px;
                align-items: center;
            }

            .klinik-modal {
                width: 100%;
                max-width: none;
                max-height: calc(100vh - 20px);

                border-radius: 14px;

                display: flex;
                flex-direction: column;
            }

            /* HEADER */
            .klinik-modal-header {
                padding: 15px 16px;
                flex-shrink: 0;
            }

            .klinik-modal-header h5 {
                font-size: 16px;
                line-height: 1.3;
            }

            .klinik-modal-header small {
                font-size: 11px;
            }

            .klinik-modal-close {
                width: 34px;
                height: 34px;

                flex-shrink: 0;

                font-size: 25px;
            }

            /* BODY */
            .klinik-modal-body {
                padding: 16px;

                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            /* IDENTITY */
            .klinik-modal-identity {
                grid-template-columns: 1fr;
                gap: 8px;

                margin-bottom: 18px;
            }

            .klinik-modal-info {
                padding: 11px 12px;
                border-radius: 9px;
            }

            .klinik-modal-info span {
                margin-bottom: 3px;
                font-size: 10px;
            }

            .klinik-modal-info strong {
                font-size: 13px;
                line-height: 1.4;

                overflow-wrap: anywhere;
                word-break: break-word;
            }

            /* FORM */
            .klinik-form-group {
                margin-bottom: 16px;
            }

            .klinik-form-group label {
                margin-bottom: 6px;
                font-size: 12px;
            }

            .klinik-form-group input {
                width: 100%;
                height: 43px;

                font-size: 13px;
                border-radius: 8px;
            }

            .klinik-form-group small {
                margin-top: 5px;
                font-size: 11px;
                line-height: 1.4;
            }

            .klinik-form-error {
                font-size: 11px;
            }

            /* FOOTER */
            .klinik-modal-footer {
                display: flex;

                gap: 8px;

                padding: 12px 16px;

                flex-shrink: 0;
            }

            .klinik-modal-footer .btn {
                flex: 1;

                min-width: 0;
                height: 40px;

                font-size: 12px;
            }

        }

        /* MODAL KONFIRMASI KLINIK */
        .klinik-confirm-overlay {
            position: fixed;
            inset: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background: rgba(0, 0, 0, .45);
            backdrop-filter: blur(2px);

            opacity: 0;
            visibility: hidden;

            transition: .2s ease;

            z-index: 100000;
        }

        .klinik-confirm-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .klinik-confirm-modal {
            width: 100%;
            max-width: 480px;

            background: #ffffff;

            border-radius: 16px;
            overflow: hidden;

            box-shadow: 0 20px 50px rgba(0, 0, 0, .18);

            transform: translateY(-15px) scale(.98);

            transition: .2s ease;
        }

        .klinik-confirm-overlay.show .klinik-confirm-modal {
            transform: translateY(0) scale(1);
        }

        /* HEADER */
        .klinik-confirm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 18px 22px;

            border-bottom: 1px solid #e9ecef;

            background: #f8f9fa;
        }

        .klinik-confirm-header h5 {
            margin: 0 0 3px;

            font-size: 17px;
            font-weight: 700;

            color: #283144;
        }

        .klinik-confirm-header small {
            font-size: 12px;

            color: #8a94a6;
        }

        .klinik-confirm-close {
            width: 36px;
            height: 36px;

            border: none;
            border-radius: 8px;

            background: transparent;

            color: #6c757d;

            font-size: 27px;
            line-height: 1;

            cursor: pointer;
        }

        .klinik-confirm-close:hover {
            background: #e9ecef;
            color: #dc3545;
        }

        /* BODY */
        .klinik-confirm-body {
            padding: 24px;
            text-align: center;
        }

        .klinik-confirm-icon {
            width: 56px;
            height: 56px;

            margin: 0 auto 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #fff3cd;

            border-radius: 50%;

            font-size: 27px;
        }

        .klinik-confirm-body h6 {
            margin-bottom: 7px;

            font-size: 16px;
            font-weight: 700;

            color: #283144;
        }

        .klinik-confirm-body>p {
            margin-bottom: 18px;

            font-size: 13px;
        }

        /* WBP */
        .klinik-confirm-wbp {
            padding: 12px 15px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;

            border-radius: 10px;

            text-align: left;

            margin-bottom: 12px;
        }

        .klinik-confirm-wbp span {
            display: block;

            margin-bottom: 4px;

            font-size: 11px;

            color: #8a94a6;
        }

        .klinik-confirm-wbp strong {
            display: block;

            font-size: 14px;

            color: #283144;
        }

        /* CHANGE */
        .klinik-change-box {
            padding: 14px 16px;

            background: #f8f9fa;

            border: 1px solid #e9ecef;

            border-radius: 10px;
        }

        .klinik-change-item {
            display: flex;
            flex-direction: column;

            gap: 6px;

            text-align: left;
        }

        .klinik-change-item+.klinik-change-item {
            margin-top: 14px;

            padding-top: 14px;

            border-top: 1px solid #e9ecef;
        }

        .klinik-change-item span {
            display: block;

            margin-bottom: 0;

            font-size: 11px;

            font-weight: 600;

            color: #8a94a6;
        }

        .klinik-change-value {
            display: flex;

            align-items: center;

            gap: 10px;

            min-width: 0;
        }

        .klinik-change-value strong {
            display: block;

            font-size: 13px;

            line-height: 1.4;

            word-break: break-word;

            overflow-wrap: anywhere;
        }

        .klinik-change-value i {
            flex-shrink: 0;

            font-size: 14px;

            color: #8a94a6;
        }

        /* WARNING */
        .klinik-confirm-warning {
            display: flex;
            align-items: flex-start;

            gap: 8px;

            margin-top: 15px;

            padding: 11px 13px;

            background: #fff8e1;

            border: 1px solid #ffe69c;

            border-radius: 9px;

            text-align: left;

            font-size: 15px;

            color: #856404;
        }

        /* FOOTER */
        .klinik-confirm-footer {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding: 16px 22px;

            border-top: 1px solid #e9ecef;
        }

        .klinik-confirm-footer .btn {
            min-width: 120px;

            height: 40px;

            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;
        }

        /* MOBILE - MODAL KONFIRMASI */
        @media (max-width: 600px) {

            .klinik-confirm-overlay {
                padding: 10px;
            }

            .klinik-confirm-modal {
                width: 100%;
                max-width: none;
                max-height: calc(100vh - 20px);

                border-radius: 14px;

                display: flex;
                flex-direction: column;
            }

            /* HEADER */
            .klinik-confirm-header {
                padding: 15px 16px;
                flex-shrink: 0;
            }

            .klinik-confirm-header h5 {
                font-size: 15px;
                line-height: 1.3;
            }

            .klinik-confirm-header small {
                font-size: 11px;
            }

            .klinik-confirm-close {
                width: 34px;
                height: 34px;

                flex-shrink: 0;

                font-size: 25px;
            }

            /* BODY */
            .klinik-confirm-body {
                padding: 18px 16px;

                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .klinik-confirm-icon {
                width: 48px;
                height: 48px;

                margin-bottom: 12px;

                font-size: 23px;
            }

            .klinik-confirm-body h6 {
                margin-bottom: 6px;
                font-size: 15px;
            }

            .klinik-confirm-body>p {
                margin-bottom: 14px;
                font-size: 12px;
                line-height: 1.5;
            }

            /* WBP */
            .klinik-confirm-wbp {
                padding: 10px 12px;
                margin-bottom: 10px;

                border-radius: 9px;
            }

            .klinik-confirm-wbp span {
                margin-bottom: 3px;
                font-size: 10px;
            }

            .klinik-confirm-wbp strong {
                font-size: 13px;
                line-height: 1.4;

                overflow-wrap: anywhere;
                word-break: break-word;
            }

            /* CHANGE */
            .klinik-change-box {
                padding: 11px 12px;
                border-radius: 9px;
            }

            .klinik-change-item {
                gap: 5px;
            }

            .klinik-change-item+.klinik-change-item {
                margin-top: 11px;
                padding-top: 11px;
            }

            .klinik-change-item span {
                font-size: 10px;
            }

            .klinik-change-value {
                gap: 7px;
                align-items: flex-start;
            }

            .klinik-change-value strong {
                font-size: 12px;
                line-height: 1.4;

                overflow-wrap: anywhere;
                word-break: break-word;
            }

            .klinik-change-value i {
                font-size: 12px;
                margin-top: 2px;
            }

            /* WARNING */
            .klinik-confirm-warning {
                gap: 7px;

                margin-top: 12px;
                padding: 10px 11px;

                font-size: 12px;
                line-height: 1.45;
            }

            /* FOOTER */
            .klinik-confirm-footer {
                display: flex;

                gap: 8px;

                padding: 12px 16px;

                flex-shrink: 0;
            }

            .klinik-confirm-footer .btn {
                flex: 1;

                min-width: 0;
                height: 40px;

                font-size: 12px;
            }

        }
    </style>

    <div class="container-fluid my-3">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

            <div>
                <h4 class="fw-bold mb-1">
                    Data WBP Klinik

                    <span class="badge bg-primary ms-2">
                        {{ number_format($totalWbpKlinik) }} Orang
                    </span>
                </h4>

                <small class="text-secondary">
                    Data WBP yang terdaftar dalam sistem klinik
                </small>
            </div>

        </div>


        {{-- STATISTIK --}}
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">

            <div class="col">
                <div class="klinik-stat-card klinik-stat-primary">

                    <div class="klinik-stat-title">
                        Total WBP Klinik
                    </div>

                    <div class="klinik-stat-value">
                        {{ number_format($totalWbpKlinik) }}
                    </div>

                    <div class="klinik-stat-description">
                        Terdaftar pada Sistem Klinik Lapas Banceuy
                    </div>

                </div>
            </div>

            <div class="col">
                <div class="klinik-stat-card klinik-stat-success">

                    <div class="klinik-stat-title">
                        Memiliki Rekam Medis
                    </div>

                    <div class="klinik-stat-value">
                        {{ number_format($totalDenganRekamMedis) }}
                    </div>

                    <div class="klinik-stat-description">
                        Nomor rekam medis tersedia
                    </div>

                </div>
            </div>

            <div class="col">
                <a href="{{ route('klinik.wbp.index', ['status' => 'tanpa_rekam_medis']) }}" class="klinik-stat-card-link">

                    <div class="klinik-stat-card klinik-stat-warning">

                        <div class="klinik-stat-title">
                            Belum Ada Rekam Medis
                        </div>

                        <div class="klinik-stat-value">
                            {{ number_format($totalTanpaRekamMedis) }}
                        </div>

                        <div class="klinik-stat-description">
                            Klik untuk melihat data
                        </div>

                    </div>

                </a>
            </div>

        </div>

        {{-- SEARCH --}}
        <div class="klinik-search">

            <input type="text" id="searchKlinik" name="search" class="form-control"
                placeholder="Cari nama WBP, No. Reg, atau nomor rekam medis..." value="{{ request('search') }}"
                autocomplete="off">

        </div>

        @if (request('status') === 'tanpa_rekam_medis')
            <div class="klinik-filter-info">

                <div>
                    <i class="bi bi-funnel me-1"></i>
                    Menampilkan WBP yang belum memiliki nomor rekam medis
                </div>

                <a href="{{ route('klinik.wbp.index') }}" class="btn btn-sm btn-outline-secondary klinik-btn-kembali">
                    <i class="bi bi-x-lg me-1"></i>
                    Kembali ke Semua Data
                </a>

            </div>
        @endif

        {{-- TABLE --}}
        <div id="klinikTableWrapper">

            <div class="klinik-table-card">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table klinik-table align-middle">

                            <thead>
                                <tr>
                                    <th class="col-rekam-medis">No. Rekam Medis</th>
                                    <th class="col-nik">NIK</th>
                                    <th class="col-nama">Nama WBP</th>
                                    <th class="col-tanggal-masuk">Tanggal Masuk Lapas</th>
                                    <th class="col-aksi">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($data as $item)
                                    <tr>

                                        {{-- NO REKAM MEDIS --}}
                                        <td>

                                            @if ($item->no_rekam_medis)
                                                <span class="klinik-rekam-medis">
                                                    {{ $item->no_rekam_medis }}
                                                </span>
                                            @else
                                                <span class="klinik-rekam-kosong">
                                                    Belum tersedia
                                                </span>
                                            @endif

                                        </td>

                                        {{-- NIK --}}
                                        <td>

                                            @if ($item->wbp?->nik)
                                                <span class="klinik-nik">
                                                    {{ $item->wbp->nik }}
                                                </span>
                                            @else
                                                <span class="klinik-nik-kosong">
                                                    Belum tersedia
                                                </span>
                                            @endif

                                        </td>

                                        {{-- NAMA WBP --}}
                                        <td>

                                            @if ($item->wbp)
                                                <div class="klinik-wbp-name">
                                                    {{ $item->wbp->nama }}
                                                </div>

                                                <span class="klinik-wbp-reg">
                                                    No. Reg:
                                                    {{ $item->wbp->no_reg_instansi ?? '-' }}
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    Data WBP tidak ditemukan
                                                </span>
                                            @endif

                                        </td>

                                        {{-- TANGGAL MASUK LAPAS --}}
                                        <td>

                                            @if ($item->wbp?->tgl_masuk_lapas)
                                                <span class="klinik-tanggal-masuk">
                                                    {{ \Carbon\Carbon::parse($item->wbp->tgl_masuk_lapas)->locale('id')->translatedFormat('d F Y') }}
                                                </span>
                                            @else
                                                <span class="klinik-tanggal-kosong">
                                                    Belum tersedia
                                                </span>
                                            @endif

                                        </td>

                                        {{-- AKSI --}}
                                        <td>

                                            <div class="d-flex gap-1">

                                                {{-- DETAIL --}}
                                                <button type="button"
                                                    class="klinik-action klinik-action-detail btn-detail-klinik"
                                                    title="Detail"
                                                    data-detail-url="{{ route('klinik.wbp.show', $item->wbp_id) }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                {{-- EDIT --}}
                                                <button type="button"
                                                    class="klinik-action klinik-action-edit btn-edit-klinik" title="Edit"
                                                    data-wbp-id="{{ $item->wbp_id }}"
                                                    data-nama="{{ $item->wbp->nama ?? '-' }}"
                                                    data-no-reg="{{ $item->wbp->no_reg_instansi ?? '-' }}"
                                                    data-nik="{{ $item->wbp->nik ?? '' }}"
                                                    data-tanggal-masuk="{{ $item->wbp->tgl_masuk_lapas ?? '' }}"
                                                    data-rekam-medis="{{ $item->no_rekam_medis ?? '' }}"
                                                    data-update-url="{{ route('klinik.wbp.update', $item->wbp_id) }}">

                                                    <i class="bi bi-pencil-square"></i>

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="klinik-empty">

                                            <div class="klinik-empty-title">
                                                Data WBP klinik tidak ditemukan
                                            </div>

                                            <div class="klinik-empty-description">
                                                Tidak ada data yang sesuai dengan pencarian.
                                            </div>

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- ================= PAGINATION ================= --}}
                    @if ($data->hasPages())
                        <div class="klinik-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div class="klinik-pagination-info">

                                Menampilkan
                                <strong>{{ $data->firstItem() }}</strong>
                                -
                                <strong>{{ $data->lastItem() }}</strong>

                                dari

                                <strong>{{ $data->total() }}</strong>
                                data

                            </div>

                            <div>
                                {{ $data->onEachSide(1)->links() }}
                            </div>

                        </div>
                    @endif

                </div>

            </div>

        </div>

        {{-- MODAL EDIT WBP KLINIK --}}
        <div id="modalEditWbpKlinik" class="klinik-modal-overlay">

            <div class="klinik-modal">

                {{-- HEADER --}}
                <div class="klinik-modal-header">

                    <div>
                        <h5>
                            Edit Data WBP Klinik
                        </h5>

                        <small>
                            Perbarui data wbp.
                        </small>
                    </div>

                    <button type="button" class="klinik-modal-close" id="btnCloseEditKlinik">

                        ×

                    </button>

                </div>

                {{-- BODY --}}
                <div class="klinik-modal-body">

                    <form id="formEditWbpKlinik" method="POST">

                        @csrf
                        @method('PUT')

                        {{-- IDENTITAS --}}
                        <div class="klinik-modal-identity">

                            <div class="klinik-modal-info">

                                <span>
                                    Nama WBP
                                </span>

                                <strong id="editKlinikNama">
                                    -
                                </strong>

                            </div>


                            <div class="klinik-modal-info">

                                <span>
                                    No. Registrasi
                                </span>

                                <strong id="editKlinikNoReg">
                                    -
                                </strong>

                            </div>


                            {{-- NIK --}}
                            <div class="klinik-modal-info">

                                <label for="editKlinikNik">
                                    NIK
                                </label>

                                <input type="text" id="editKlinikNik" name="nik" class="form-control" maxlength="30"
                                    placeholder="Masukkan NIK">

                            </div>


                            {{-- TANGGAL MASUK LAPAS --}}
                            <div class="klinik-modal-info">

                                <label for="editKlinikTanggalMasuk">
                                    Tanggal Masuk Lapas
                                </label>

                                <input type="date" id="editKlinikTanggalMasuk" name="tgl_masuk_lapas"
                                    class="form-control">

                            </div>

                        </div>


                        {{-- NO REKAM MEDIS --}}
                        <div class="klinik-form-group">

                            <label for="editKlinikRekamMedis">
                                No. Rekam Medis
                            </label>

                            <input type="text" id="editKlinikRekamMedis" name="no_rekam_medis" class="form-control"
                                maxlength="50" placeholder="Contoh: 25/XI/650">

                            <small>
                                Masukkan nomor rekam medis sesuai kartu pasien.
                            </small>

                            <div id="editKlinikError" class="klinik-form-error">
                            </div>

                        </div>

                    </form>

                </div>

                {{-- FOOTER --}}
                <div class="klinik-modal-footer">

                    <button type="button" class="btn btn-outline-secondary" id="btnCancelEditKlinik">
                        Batal
                    </button>

                    <button type="submit" form="formEditWbpKlinik" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </div>

        </div>

        {{-- MODAL KONFIRMASI EDIT WBP KLINIK --}}
        <div id="modalKonfirmasiKlinik" class="klinik-confirm-overlay">

            <div class="klinik-confirm-modal">

                {{-- HEADER --}}
                <div class="klinik-confirm-header">

                    <div>
                        <h5>
                            Konfirmasi Perubahan
                        </h5>

                        <small>
                            Pastikan data yang dimasukkan sudah benar.
                        </small>
                    </div>

                    <button type="button" class="klinik-confirm-close" id="btnCloseKonfirmasiKlinik">

                        ×

                    </button>

                </div>


                {{-- BODY --}}
                <div class="klinik-confirm-body">

                    <div class="klinik-confirm-icon">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>

                    <h6>
                        Simpan Perubahan Data WBP?
                    </h6>

                    <p class="text-secondary">
                        Anda akan mengubah data WBP berikut:
                    </p>


                    {{-- NAMA --}}
                    <div class="klinik-confirm-wbp">

                        <span>
                            WBP
                        </span>

                        <strong id="confirmKlinikNama">
                            -
                        </strong>

                    </div>


                    <div class="klinik-change-box">

                        <div class="klinik-change-item">

                            <span>NIK</span>

                            <div class="klinik-change-value">
                                <strong id="confirmKlinikNikLama" class="text-secondary">
                                    -
                                </strong>

                                <i class="bi bi-arrow-right"></i>

                                <strong id="confirmKlinikNikBaru" class="text-primary">
                                    -
                                </strong>
                            </div>

                        </div>


                        <div class="klinik-change-item">

                            <span>Tanggal Masuk Lapas</span>

                            <div class="klinik-change-value">
                                <strong id="confirmKlinikTanggalLama" class="text-secondary">
                                    -
                                </strong>

                                <i class="bi bi-arrow-right"></i>

                                <strong id="confirmKlinikTanggalBaru" class="text-primary">
                                    -
                                </strong>
                            </div>

                        </div>


                        <div class="klinik-change-item">

                            <span>No. Rekam Medis</span>

                            <div class="klinik-change-value">
                                <strong id="confirmKlinikLama" class="text-secondary">
                                    -
                                </strong>

                                <i class="bi bi-arrow-right"></i>

                                <strong id="confirmKlinikBaru" class="text-primary">
                                    -
                                </strong>
                            </div>

                        </div>

                    </div>

                    <div class="klinik-confirm-warning">

                        <span>
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </span>

                        <span>
                            Pastikan nomor rekam medis sesuai dengan kartu pasien.
                        </span>

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="klinik-modal-footer">

                    <button type="button" class="btn btn-outline-secondary" id="btnCancelKonfirmasiKlinik">
                        Batal
                    </button>

                    <button type="button" class="btn btn-primary" id="btnConfirmKlinik">

                        Simpan Perubahan

                    </button>

                </div>

            </div>

        </div>
    @endsection

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ELEMENT — SEARCH TABLE
            const searchInput = document.getElementById('searchKlinik');
            const tableWrapper = document.getElementById('klinikTableWrapper');

            const filterUrl = @json(route('klinik.wbp.filter'));
            const wbpBaseUrl = @json(url('/admin-banceuy/klinik/wbp'));

            // STATUS FILTER NOW
            const urlParams = new URLSearchParams(window.location.search);
            const currentStatus = urlParams.get('status') || '';

            // ELEMENT — MODAL EDIT WBP KLINIK
            const modal = document.getElementById('modalEditWbpKlinik');
            const form = document.getElementById('formEditWbpKlinik');
            const nama = document.getElementById('editKlinikNama');
            const noReg = document.getElementById('editKlinikNoReg');
            const rekamMedis = document.getElementById('editKlinikRekamMedis');
            const nik = document.getElementById('editKlinikNik');
            const tanggalMasuk = document.getElementById('editKlinikTanggalMasuk');
            const errorBox = document.getElementById('editKlinikError');
            const closeButton = document.getElementById('btnCloseEditKlinik');
            const cancelButton = document.getElementById('btnCancelEditKlinik');

            // ELEMENT — MODAL KONFIRMASI
            const confirmModal = document.getElementById('modalKonfirmasiKlinik');
            const confirmCloseButton = document.getElementById('btnCloseKonfirmasiKlinik');
            const confirmCancelButton = document.getElementById('btnCancelKonfirmasiKlinik');
            const confirmButton = document.getElementById('btnConfirmKlinik');
            const confirmNama = document.getElementById('confirmKlinikNama');
            const confirmNikLama = document.getElementById('confirmKlinikNikLama');
            const confirmNikBaru = document.getElementById('confirmKlinikNikBaru');
            const confirmTanggalLama = document.getElementById('confirmKlinikTanggalLama');
            const confirmTanggalBaru = document.getElementById('confirmKlinikTanggalBaru');
            const confirmRekamLama = document.getElementById('confirmKlinikLama');
            const confirmRekamBaru = document.getElementById('confirmKlinikBaru');

            // HELPER ESC HTML
            function escapeHtml(value) {

                if (value === null || value === undefined) {
                    return '';
                }

                return String(value)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            // TANGGAL LOCALE ID
            function formatTanggalIndonesia(value) {

                if (!value) {
                    return null;
                }

                const dateOnly = String(value).substring(0, 10);
                const parts = dateOnly.split('-');

                if (parts.length !== 3) {
                    return value;
                }

                const year = parseInt(parts[0]);
                const month = parseInt(parts[1]) - 1;
                const day = parseInt(parts[2]);

                const date = new Date(year, month, day);

                return new Intl.DateTimeFormat('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                }).format(date);
            }

            // RENDER TABLE
            function renderTable(response) {

                let rows = '';

                if (!response.data || response.data.length === 0) {

                    rows = `
                    <tr>
                        <td colspan="5" class="klinik-empty">

                            <div class="klinik-empty-title">
                                Data WBP klinik tidak ditemukan
                            </div>

                            <div class="klinik-empty-description">
                                Tidak ada data yang sesuai dengan pencarian.
                            </div>

                        </td>
                    </tr>
                `;

                } else {

                    response.data.forEach(function(item, index) {

                        const namaWbp =
                            item.wbp?.nama ?? '-';

                        const noRegWbp =
                            item.wbp?.no_reg_instansi ?? '-';

                        const nik =
                            item.wbp?.nik ?? '';

                        const tanggalMasuk =
                            formatTanggalIndonesia(
                                item.wbp?.tgl_masuk_lapas
                            );

                        const noRekamMedis =
                            item.no_rekam_medis ?? '';

                        const showUrl =
                            `${wbpBaseUrl}/${item.wbp_id}`;

                        const updateUrl =
                            `${wbpBaseUrl}/${item.wbp_id}`;

                        rows += `
                            <tr>

                                {{-- NO REKAM MEDIS --}}
                                <td>
                                    ${
                                        noRekamMedis
                                        ? `
                                                                        <span class="klinik-rekam-medis">
                                                                            ${escapeHtml(noRekamMedis)}
                                                                        </span>
                                                                            `
                                    : `
                                                                        <span class="klinik-rekam-kosong">
                                                                            Belum tersedia
                                                                        </span>
                                                                            `
                                    }
                                </td>

                                {{-- NIK --}}
                                <td>
                                    ${
                                        nik
                                        ? `
                                                                        <span class="klinik-nik">
                                                                                ${escapeHtml(nik)}
                                                                            </span>
                                                                            `
                                                : `
                                                                        <span class="klinik-nik-kosong">
                                                                            Belum tersedia
                                                                        </span>
                                                                            `
                                    }
                                </td>

                                {{-- NAMA WBP --}}
                                <td>

                                    ${
                                        item.wbp
                                        ? `
                                                                        <div class="klinik-wbp-name">
                                                                            ${escapeHtml(namaWbp)}
                                                                        </div>

                                                                            <span class="klinik-wbp-reg">
                                                                            No. Reg:
                                                                                ${escapeHtml(noRegWbp)}
                                                                            </span>
                                                                            `
                                            : `
                                                                        <span class="text-danger">
                                                                            Data WBP tidak ditemukan
                                                                        </span>
                                                                        `
                                    }

                                </td>

                                {{-- TANGGAL MASUK LAPAS --}}
                                <td>

                                    ${
                                        tanggalMasuk
                                        ? `
                                                                            <span class="klinik-tanggal-masuk">
                                                                                ${escapeHtml(tanggalMasuk)}
                                                                            </span>
                                                                                    `
                                        : `
                                                                            <span class="klinik-tanggal-kosong">
                                                                                Belum tersedia
                                                                            </span>
                                                                                    `
                                    }

                                </td>

                                {{-- AKSI --}}
                                <td>

                                    <div class="d-flex gap-1">

                                        <a href="${showUrl}"
                                            class="klinik-action klinik-action-detail"
                                            title="Detail">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <button type="button"
                                            class="klinik-action klinik-action-edit btn-edit-klinik"
                                            title="Edit"

                                            data-wbp-id="${escapeHtml(item.wbp_id)}"
                                            data-nama="${escapeHtml(namaWbp)}"
                                            data-no-reg="${escapeHtml(noRegWbp)}"
                                            data-nik="${escapeHtml(nik)}"
                                            data-tanggal-masuk="${escapeHtml(item.wbp?.tgl_masuk_lapas ?? '')}"
                                            data-rekam-medis="${escapeHtml(noRekamMedis)}"
                                            data-update-url="${updateUrl}">

                                            <i class="bi bi-pencil-square"></i>

                                        </button>

                                    </div>

                                </td>

                        </tr>
                    `;
                    });

                }

                tableWrapper.innerHTML = `
                <div class="klinik-table-card">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table klinik-table align-middle">

                                <thead>
                                    <tr>
                                        <th class="col-rekam-medis">
                                            No. Rekam Medis
                                        </th>

                                        <th class="col-nik">
                                            NIK
                                        </th>

                                        <th class="col-nama">
                                            Nama WBP
                                        </th>

                                        <th class="col-tanggal-masuk">
                                            Tanggal Masuk Lapas
                                        </th>

                                        <th class="col-aksi">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    ${rows}
                                </tbody>

                            </table>

                        </div>

                        ${renderPagination(response)}

                    </div>

                </div>
            `;
            }

            // RENDER PAGINATION
            function renderPagination(response) {

                if (
                    !response.last_page ||
                    response.last_page <= 1
                ) {
                    return '';
                }

                const currentPage = response.current_page;
                const lastPage = response.last_page;

                let pagination = '';

                // PREVIOUS
                pagination += `
                <li class="page-item ${currentPage <= 1 ? 'disabled' : ''}">

                    <button
                        type="button"
                        class="page-link btn-klinik-page"
                        data-page="${currentPage - 1}"
                        ${currentPage <= 1 ? 'disabled' : ''}>

                        &lsaquo;

                    </button>

                </li>
            `;

                // NOMOR HALAMAN
                const startPage =
                    Math.max(1, currentPage - 2);

                const endPage =
                    Math.min(lastPage, currentPage + 2);


                if (startPage > 1) {

                    pagination += `
                    <li class="page-item">

                        <button
                            type="button"
                            class="page-link btn-klinik-page"
                            data-page="1">

                            1

                        </button>

                    </li>
                `;

                    if (startPage > 2) {

                        pagination += `
                        <li class="page-item disabled">
                            <span class="page-link">
                                ...
                            </span>
                        </li>
                    `;
                    }
                }

                for (
                    let page = startPage; page <= endPage; page++
                ) {

                    pagination += `
                    <li class="page-item ${page === currentPage ? 'active' : ''}">

                        <button
                            type="button"
                            class="page-link btn-klinik-page"
                            data-page="${page}">

                            ${page}

                        </button>

                    </li>
                `;
                }

                if (endPage < lastPage) {

                    if (endPage < lastPage - 1) {

                        pagination += `
                        <li class="page-item disabled">
                            <span class="page-link">
                                ...
                            </span>
                        </li>
                    `;
                    }

                    pagination += `
                    <li class="page-item">

                        <button
                            type="button"
                            class="page-link btn-klinik-page"
                            data-page="${lastPage}">

                            ${lastPage}

                        </button>

                    </li>
                `;
                }

                // NEXT
                pagination += `
                <li class="page-item ${currentPage >= lastPage ? 'disabled' : ''}">

                    <button
                        type="button"
                        class="page-link btn-klinik-page"
                        data-page="${currentPage + 1}"
                        ${currentPage >= lastPage ? 'disabled' : ''}>

                        &rsaquo;

                    </button>

                </li>
            `;

                return `
                <div class="klinik-pagination d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="klinik-pagination-info">

                        Menampilkan

                        <strong>
                            ${response.from ?? 0}
                        </strong>

                        -

                        <strong>
                            ${response.to ?? 0}
                        </strong>

                        dari

                        <strong>
                            ${response.total ?? 0}
                        </strong>

                        data

                    </div>

                    <nav>
                        <ul class="pagination mb-0">
                            ${pagination}
                        </ul>
                    </nav>

                </div>
            `;
            }

            // LOAD DATA AJAX
            async function loadKlinikData(page = 1) {

                const search =
                    searchInput.value.trim();

                // LOADING

                tableWrapper.innerHTML = `
                <div class="klinik-table-card">

                    <div class="card-body text-center py-5">

                        <div
                            class="spinner-border text-primary mb-3"
                            role="status">
                        </div>

                        <div>
                            Memuat data...
                        </div>

                    </div>

                </div>
            `;

                // PARAMATER
                const params = new URLSearchParams();

                params.set('page', page);

                if (search !== '') {
                    params.set('search', search);
                }

                if (currentStatus !== '') {
                    params.set('status', currentStatus);
                }

                try {

                    const response = await fetch(
                        `${filterUrl}?${params.toString()}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );


                    if (!response.ok) {

                        throw new Error(
                            `HTTP ${response.status}`
                        );
                    }


                    const data =
                        await response.json();


                    renderTable(data);


                } catch (error) {

                    console.error(
                        'Gagal memuat WBP Klinik:',
                        error
                    );

                    tableWrapper.innerHTML = `
                    <div class="alert alert-danger">

                        Gagal memuat data WBP Klinik.

                    </div>
                `;
                }
            }

            // LIVE SEARCH
            let searchTimer = null;
            searchInput.addEventListener('input', function() {

                const search = this.value.trim();

                clearTimeout(searchTimer);

                searchTimer = setTimeout(function() {

                    loadKlinikData(1);

                }, 300);

            });

            // PAGINATION AJAX
            tableWrapper.addEventListener('click', function(event) {

                const button =
                    event.target.closest('.btn-klinik-page');

                if (!button || button.disabled) {
                    return;
                }

                const page =
                    parseInt(button.dataset.page);

                if (!page || page < 1) {
                    return;
                }

                loadKlinikData(page);

            });

            // OPEN MODAL EDIT AFTER AJAX LOAD DATA
            tableWrapper.addEventListener('click', function(event) {

                const button =
                    event.target.closest('.btn-edit-klinik');

                if (!button) {
                    return;
                }

                nama.textContent = button.dataset.nama || '-';
                noReg.textContent = button.dataset.noReg || '-';
                nik.value = button.dataset.nik || '';
                tanggalMasuk.value = button.dataset.tanggalMasuk || '';
                rekamMedis.value = button.dataset.rekamMedis || '';
                form.dataset.nikLama = button.dataset.nik || '';
                form.dataset.tanggalMasukLama = button.dataset.tanggalMasuk || '';
                form.dataset.rekamMedisLama = button.dataset.rekamMedis || '';

                form.action = button.dataset.updateUrl;
                form.dataset.wbpId = button.dataset.wbpId;

                console.log('UPDATE URL:', form.action);

                errorBox.textContent = '';

                modal.classList.add('show');

                setTimeout(function() {

                    rekamMedis.focus();

                }, 200);

            });

            // TUTUP MODAL
            function closeModal() {

                modal.classList.remove('show');

                errorBox.textContent = '';

            }

            // BUTTON CLOSE MODAL EDIT
            if (closeButton) {

                closeButton.addEventListener(
                    'click',
                    closeModal
                );

            }

            // BUTTON BATAL MODAL EDIT
            if (cancelButton) {

                cancelButton.addEventListener(
                    'click',
                    closeModal
                );

            }

            // BUTTON CLOSE MODAL KONFIRMASI
            if (confirmCloseButton) {

                confirmCloseButton.addEventListener('click', function() {

                    confirmModal.classList.remove('show');

                    modal.classList.add('show');

                });

            }

            // BUTTON BATAL MODAL KONFIRMASI
            if (confirmCancelButton) {

                confirmCancelButton.addEventListener('click', function() {

                    confirmModal.classList.remove('show');

                    modal.classList.add('show');

                });

            }

            // SUBMIT FORM EDIT → BUKA KONFIRMASI
            if (form) {

                form.addEventListener('submit', function(event) {

                    event.preventDefault();

                    // NAMA WBP
                    confirmNama.textContent =
                        nama.textContent || '-';


                    // NIK
                    const nikLama =
                        form.dataset.nikLama ?? '';

                    const nikBaru =
                        nik.value.trim();

                    confirmNikLama.textContent =
                        nikLama || '-';

                    confirmNikBaru.textContent =
                        nikBaru || '-';


                    // TANGGAL MASUK LAPAS
                    const tanggalLama =
                        form.dataset.tanggalMasukLama ?? '';

                    const tanggalBaru =
                        tanggalMasuk.value || '';

                    confirmTanggalLama.textContent =
                        tanggalLama ?
                        formatTanggalIndonesia(tanggalLama) :
                        '-';

                    confirmTanggalBaru.textContent =
                        tanggalBaru ?
                        formatTanggalIndonesia(tanggalBaru) :
                        '-';


                    // NO REKAM MEDIS
                    const rekamLama =
                        form.dataset.rekamMedisLama ?? '';

                    const rekamBaru =
                        rekamMedis.value.trim();

                    confirmRekamLama.textContent =
                        rekamLama || '-';

                    confirmRekamBaru.textContent =
                        rekamBaru || '-';


                    // TUTUP MODAL EDIT
                    modal.classList.remove('show');

                    // BUKA MODAL KONFIRMASI
                    confirmModal.classList.add('show');

                });

            }

            // KONFIRMASI SIMPAN
            if (confirmButton) {

                confirmButton.addEventListener('click', async function(event) {

                    event.preventDefault();

                    // Cegah double click
                    if (confirmButton.disabled) {
                        return;
                    }

                    confirmButton.disabled = true;

                    confirmButton.innerHTML = `
                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true">
                        </span>
                        Menyimpan...
                    `;

                    try {

                        console.log('FORM ACTION:', form.action);
                        console.log('FORM METHOD:', form.method);
                        console.log('FORM DATA:', [...new FormData(form).entries()]);

                        const response = await fetch(form.action, {
                            method: 'POST',

                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).getAttribute('content'),

                                'Accept': 'application/json',

                                'X-Requested-With': 'XMLHttpRequest'
                            },

                            body: new FormData(form)
                        });

                        // Ambil response sebagai text dulu
                        const responseText = await response.text();

                        console.log('STATUS:', response.status);
                        console.log('CONTENT-TYPE:', response.headers.get('content-type'));
                        console.log('RESPONSE:', responseText);

                        let data;

                        try {
                            data = JSON.parse(responseText);
                        } catch (jsonError) {

                            console.error('========== ERROR RESPONSE ==========');
                            console.error('STATUS:', response.status);
                            console.error('CONTENT-TYPE:', response.headers.get('content-type'));
                            console.error('URL:', form.action);
                            console.error('RESPONSE MENTAH:', responseText);
                            console.error('====================================');

                            throw new Error(
                                'Response server bukan JSON. Cek Console untuk detail.'
                            );
                        }

                        // VALIDASI/ERROR
                        if (!response.ok) {

                            if (response.status === 422 && data.errors) {

                                const firstError = Object.values(data.errors)
                                    .flat()[0];

                                throw new Error(
                                    firstError || 'Data yang dimasukkan tidak valid.'
                                );
                            }

                            throw new Error(
                                data.message ||
                                'Gagal menyimpan perubahan data.'
                            );
                        }

                        // BERHASIL
                        confirmModal.classList.remove('show');

                        confirmButton.disabled = false;

                        confirmButton.innerHTML = 'Simpan Perubahan';

                        // UPDATE DATA DI TABEL TANPA REFRESH
                        const wbpId = form.querySelector('input[name="wbp_id"]')?.value ||
                            form.dataset.wbpId;

                        const nikBaru = form.querySelector('input[name="nik"]')?.value.trim() || '';
                        const tanggalMasukBaru =
                            form.querySelector('input[name="tgl_masuk_lapas"]')?.value || '';
                        const rekamMedisBaru =
                            form.querySelector('input[name="no_rekam_medis"]')?.value.trim() || '';

                        // Cari row berdasarkan tombol edit yang memiliki wbp_id yang sama
                        const editButton = tableWrapper.querySelector(
                            ko `.btn-edit-klinik[data-wbp-id="${CSS.escape(String(wbpId))}"]`
                        );

                        if (editButton) {

                            // Update dataset tombol edit
                            editButton.dataset.nik = nikBaru;
                            editButton.dataset.tanggalMasuk = tanggalMasukBaru;
                            editButton.dataset.rekamMedis = rekamMedisBaru;

                            // Cari row tabel
                            const row = editButton.closest('tr');

                            if (row) {

                                // Kolom:
                                // 0 = No Rekam Medis
                                // 1 = NIK
                                // 2 = Nama WBP
                                // 3 = Tanggal Masuk
                                // 4 = Aksi

                                const cells = row.querySelectorAll('td');

                                if (cells.length >= 4) {

                                    // No Rekam Medis
                                    cells[0].textContent = rekamMedisBaru || 'Belum tersedia';

                                    // NIK
                                    cells[1].textContent = nikBaru || 'Belum tersedia';

                                    // Tanggal Masuk Lapas
                                    if (tanggalMasukBaru) {
                                        const tanggal = new Date(tanggalMasukBaru + 'T00:00:00');

                                        cells[3].textContent = tanggal.toLocaleDateString(
                                            'id-ID', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: 'numeric'
                                            }
                                        );
                                    } else {
                                        cells[3].textContent = 'Belum tersedia';
                                    }
                                }
                            }
                        }

                        // JIKA FILTER "TANPA REKAM MEDIS"
                        if (currentStatus === 'tanpa_rekam_medis' && rekamMedisBaru !== '') {

                            if (editButton) {

                                const row = editButton.closest('tr');

                                if (row) {
                                    row.remove();
                                }
                            }
                        }

                        // NOTIFIKASI SUKSES
                        if (typeof showToast === 'function') {
                            showToast(data.message, 'success');
                        }

                    } catch (error) {

                        console.error('Gagal menyimpan:', error);

                        confirmButton.disabled = false;

                        confirmButton.innerHTML = 'Simpan Perubahan';

                        alert(error.message);
                    }

                });

            }

            // KLIK OVERLAY MODAL EDIT
            modal.addEventListener('click', function(event) {

                if (event.target === modal) {

                    closeModal();

                }

            });

            // KLIK OVERLAY MODAL KONFIRMASI
            confirmModal.addEventListener('click', function(event) {

                if (event.target === confirmModal) {

                    confirmModal.classList.remove('show');

                    modal.classList.add('show');

                }

            });

            // ESC
            document.addEventListener('keydown', function(event) {

                if (event.key !== 'Escape') {
                    return;
                }


                // Jika konfirmasi terbuka → kembali ke Edit
                if (confirmModal.classList.contains('show')) {

                    confirmModal.classList.remove('show');

                    modal.classList.add('show');

                    return;

                }


                // Jika Edit terbuka → tutup Edit
                if (modal.classList.contains('show')) {

                    closeModal();

                }

            });

        });
    </script>
