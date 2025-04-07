<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bootstrap Test</title>
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
</head>

<body>
    <div class="container mt-5">
        <!-- Button Component -->
        <button class="btn btn-primary">Primary Button</button>
        <button class="btn btn-success">Success Button</button>

        <!-- Alert Component -->
        <div class="alert alert-primary mt-3" role="alert">
            This is a primary alert!
        </div>

        <!-- Card Component -->
        <div class="card mt-3" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Card Title</h5>
                <p class="card-text">Some quick example text for the card.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
        </div>

        <!-- Modal Component -->
        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Launch demo modal
        </button>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Modal content here
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
