    <section class="hero-section">
      <div class="hero-content fade-in" id="heroContent">
        <h1>Explore! Discover! Live!</h1>
        <p>The best hotel and event reservation for your family!</p>
      </div>
    </section>

    <section class="about-hotel-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="about-description">
                        <?php 
                            if (!empty($descriptions)) {
                                foreach ($descriptions as $description) {
                                    echo  htmlspecialchars($description['title']) . "<br>";
                                }
                            } else {
                                echo "No descriptions available.";
                            }
                        ?>
                    </h5>
                    <div class="about-content">
                        <?php 
                            if (!empty($descriptions)) {
                                foreach ($descriptions as $description) {
                                    echo  htmlspecialchars($description['content']) . "<br>";
                                }
                            } else {
                                echo "No descriptions available.";
                            }
                        ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-images">
                        <div class="img-top">
                            <img src="../../../public/assets/img/backgrounds/loginbg.jpg" alt="Hotel View" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>