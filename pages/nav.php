<style>
        .sidebar {
            height: 100vh; /* Full-height: remove this if you want "auto" height */
            width: 50px; /* Initial width */
            position: fixed; /* Fixed Sidebar (stay in place on scroll) */
            z-index: 1; /* Stay on top */
            top: 0; /* Stay at the top */
            left: 0;
            background-color: #333; /* cyan */
            overflow-x: hidden; /* Disable horizontal scroll */
            padding-top: 20px;
            transition: width 0.5s ease;
        }

        .sidebar a {
            padding: 10px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: flex;
            align-items: center;
            transition: padding 0.5s ease;
        }

        .sidebar a:hover {
            background-color: #0097a7; /* Dark grey */
            padding-left: 20px;
        }

        .sidebar i {
            min-width: 20px;
            text-align: center;
        }

        .link-text {
            display: none;
            margin-left: 10px;
        }

        .content {
            margin-left: 250px; /* Same as the width of the sidebar */
            padding: 1px 16px;
            height: 1000px; /* Sample height */
        }
        
        .main-content {
            margin-left: 250px; /* Adjust this value to match the width of your sidebar */
            padding: 20px;
        }

        .sidebar:hover {
            width: 200px; /* Width on hover */
        }

        .sidebar:hover .link-text {
            display: inline;
        }
    </style>
