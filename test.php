<style>
        /* General container styling */
        .container {
            font-family: "Arial", sans-serif; /* Sets the font family to Arial */
            font-size: 14px; /* Sets the base font size */
            max-width: 500px; /* Matches the max-width of the signup form */
            margin: 20px auto; /* Centered with smaller top margin */
            background-color: #f8f9fa;
            padding: 10px; /* Matches the padding of the signup form */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form styling */
        form {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Input field styling */
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Button styling */
        button {
            width: 100%;
            background-color: #0097a7;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Link styling */
        a {
            display: block;
            margin-top: 10px;
            text-align: center;
        }

        /* Alert message styling */
        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #721c24; /* Default text color for error */
            background-color: #f8d7da; /* Default background color for error */
            border-color: #f5c6cb; /* Default border color for error */
        }

        /* Success message styling */
        .alert.alert-success {
            color: #155724; /* Green text color for success */
            background-color: #d4edda; /* Green background color for success */
            border-color: #c3e6cb; /* Green border color for success */
        }
    </style>


<button class="back-button" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
.back-button {
  background-color: transparent;
  color: #6c757d;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  transition: color 0.3s, transform 0.2s;
  font-size: 20px;
  margin-bottom: 20px;
}