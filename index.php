<?php
    require_once 'auth.php';
    require_once './classes/Session.php';
    require_once './classes/Offer.php';

    $session = Session::getInstance();
    $offers = Offer::findAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--  Bootstrap 5 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--  Bootstrap 5 JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="main.css">

    <!--  Fontawesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--  Jquery  -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <!--  Datepicker  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--  Sweetalert2  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--  Custom JS  -->
    <script src="./javascript/main.js" defer></script>

    <title>T-COM | Dashboard</title>
</head>
<body>

    <div id="edit-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php include 'form.php' ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-save" form="offer-form">Save</button>
                </div>
            </div>
        </div>
    </div>

    <section id="header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand mt-2 mt-lg-0" href="index.php">
                <img
                        src="./images/telekom.png"
                        height="30"
                        alt="Slovak Telekom"
                />
            </a>
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <?php if($session->get('loggedIn')): ?>
                        <li class="nav-item">
                            <form action="logout.php" method="POST">
                                <input type="hidden" name="logout" value="logout" />
                                <a class="nav-link" onclick="this.parentNode.submit();" style="cursor: pointer">Logout (<?= $session->get('username') ?>)</a>
                            </form>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </section>
    <main>
        <div class="container mt-5">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit-modal" data-title="Create offer">Add new offer</button>

            <?php if(!empty($offers)): ?>
                <table class="table table-sm table-striped mt-2">
                <thead>
                <tr>
                    <th scope="col">Valid until</th>
                    <th scope="col">Customer name</th>
                    <th scope="col">Comment</th>
                    <th scope="col">Discount</th>
                    <th scope="col">Products</th>
                    <th scope="col">Price</th>
                    <th scope="col">Discount price</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php /** @var Offer $offer */
                    foreach($offers as $id => $offer): ?>
                        <tr>
                            <td><?= $offer->valid_until ?></td>
                            <td><?= $offer->customer_name ?></td>
                            <td><?= $offer->comment ?></td>
                            <td><?= $offer->discount_percent ?> %</td>
                            <td>
                                <?php /** @var OfferProduct $offerProduct */
                                foreach($offer->offerProducts as $offerProduct): ?>
                                    <?php if(isset($offerProduct->product->image)): ?>
                                        <div class="row mb-1">
                                            <div class="col-3">
                                                <img src=".<?= $offerProduct->product->image ?>" alt="<?= $offerProduct->product->name ?>" style="width: 50px">
                                            </div>
                                            <div class="col-3">
                                                <p><?= $offerProduct->product->name ?></p>
                                            </div>
                                            <div class="col-3">
                                                <p><?= $offerProduct->product->price ?> €</p>
                                            </div>
                                            <div class="col-3">
                                                <p><?= $offerProduct->amount ?> ks</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </td>
                            <td><?= $offer->offer_price ?> €</td>
                            <td><?= $offer->discount_price ?> €</td>
                            <td>
                                <div class="action-buttons d-flex gap-1 justify-content-center">
                                    <button class="btn btn-primary btn-sm btn-update" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-modal" data-title="Edit offer" data-id="<?= $offer->id?>"><i class="fas fa-pencil"></i></button>
                                    <form class="delete-form" method="POST" action="delete_offer.php">
                                        <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Delete"><i class="fas fa-trash"></i></button>
                                        <input type="hidden" value="<?= $offer->id ?>" name="Offer[id]">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <h3 class="mt-3">No offers available.</h3>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
