<style>
    footer {
        background-color: #f8f9fa;
        padding: 20px 0;
        text-align: center;
    }
    footer .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    footer p {
        margin: 0;
        color: #6c757d;
    }
    footer ul {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
        display: flex;
        justify-content: center;
    }
    footer ul li {
        margin: 0 10px;
    }
    footer ul li a {
        color: #0097a7;
        text-decoration: none;
    }
    footer ul li a:hover {
        text-decoration: underline;
    }
</style>

<footer>
    <div class="container">
        <p>&copy; <?php echo date("Y"); ?> Your Company. All rights reserved.</p>
        <ul>
            <li><a href="/privacy-policy">Privacy Policy</a></li>
            <li><a href="/terms-of-service">Terms of Service</a></li>
            <li><a href="/contact">Contact Us</a></li>
        </ul>
    </div>
</footer>
