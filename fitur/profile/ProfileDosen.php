<?php
require "IProfile.php";

class ProfileDosen implements IProfile
{
     public function getProfileUser($id_dosen)
     {
          global $koneksi;
          $nama_dosen = '';
          $role = '';
          $sql =    "SELECT d.id_dosen, d.nama, u.role
                    FROM dosen AS d
                    JOIN user AS u ON d.id_user = u.id_user
                    WHERE d.id_dosen = '$id_dosen'";
          $result = $koneksi->query($sql);

          if ($result) {
               while ($row = $result->fetch_assoc()) {
                    $id = $row['id_dosen'];
                    $nama_dosen = $row['nama'];
                    $role = $row['role'];
               }
          } else {
               echo "Data kosong";
          }

          $sql = "SELECT foto FROM user WHERE username = '$id_dosen'";
          $result = $koneksi->query($sql);

          if ($result && $result->num_rows > 0) {
               $row = $result->fetch_assoc();
               $path_gambar = $row['foto'];
          }

          return [
               'nama_dosen' => $nama_dosen,
               'role' => $role,
               'path_gambar' => $path_gambar
          ];
     }

     // get dosen by id
     function getUserById($username)
     {
          global $koneksi;
          $sql = "SELECT * FROM dosen WHERE id_dosen = '$username'";
          $result = $koneksi->query($sql);
          $row = $result->fetch_assoc();
          return $row;
     }

     // get image user
     function getProfileImagePath($username)
     {
          global $koneksi;
          $sql = "SELECT foto FROM user WHERE username = '$username'";
          $result = $koneksi->query($sql);

          if ($result && $result->num_rows > 0) {
               $row = $result->fetch_assoc();
               $path_gambar = $row['foto'];

               return $path_gambar;
          } else {
               return "Gambar profil tidak ditemukan";
          }
     }

     // get semua data user
     function getUserData($username)
     {
          global $koneksi;
          $sql = "SELECT * FROM user WHERE id_user = '$username'";
          $result = $koneksi->query($sql);
          $row = $result->fetch_assoc();
          return $row;
     }

     // tampilan profile dosen
     public function profile()
     {
          require "../../koneksi/koneksi.php";
          require "../../fungsi/session.php";
          ob_start();
?>
          <main id="main" class="main">
               <div class="pagetitle">
                    <h1>Profile</h1>
               </div><!-- End Page Title -->

               <section class="section profile">
                    <div class="row">
                         <div class="col-xl-4">

                              <div class="card">
                                   <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                                        <?php
                                        $id_dosen = $_SESSION["username"];
                                        $data = $this->getProfileUser($id_dosen);
                                        $nama_dosen = $data["nama_dosen"];
                                        $role = $data["role"];
                                        $path_gambar = $data["path_gambar"];

                                        ?>

                                        <img src="<?php echo $path_gambar; ?>" alt="Profile" class="rounded-circle img-thumbnail" style="width: 300px; height: 120px; object-fit: cover;">
                                        <h2 style="text-align: center;"><?php echo $nama_dosen ?></h2>
                                        <span>--</span>
                                        <h6 style="text-align: center;"><?php echo $role ?></h6>
                                        <div class="social-links mt-2">
                                             <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                                             <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                                             <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                                             <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                                        </div>
                                   </div>
                              </div>

                         </div>

                         <div class="col-xl-8">

                              <div class="card">
                                   <div class="card-body pt-3">
                                        <ul class="nav nav-tabs nav-tabs-bordered">

                                             <li class="nav-item">
                                                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                             </li>

                                             <li class="nav-item">
                                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                                             </li>
                                             <li class="nav-item">
                                                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Edit Password</button>
                                             </li>

                                        </ul>
                                        <div class="tab-content pt-2">
                                             <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                                  <h5 class="card-title">Profile Details</h5>

                                                  <div class="row">
                                                       <div class="col-lg-3 col-md-4 label">NIP</div>
                                                       <div class="col-lg-9 col-md-8"><?php echo $id_dosen ?></div>
                                                  </div>

                                                  <div class="row">
                                                       <div class="col-lg-3 col-md-4 label ">Nama</div>
                                                       <div class="col-lg-9 col-md-8"><?php echo $nama_dosen ?></div>
                                                  </div>

                                                  <div class="row">
                                                       <div class="col-lg-3 col-md-4 label ">Role</div>
                                                       <div class="col-lg-9 col-md-8"><?php echo $role ?></div>
                                                  </div>
                                             </div>

                                             <div class="tab-pane fade profile-edit pt-3" id="profile-edit">
                                                  <h5 class="card-title">Edit Profile</h5>
                                                  <!-- Profile Edit Form -->
                                                  <!-- Profile Image Edit Form -->
                                                  <form method="post" action="edit-profile.php" enctype="multipart/form-data">
                                                       <div class="row mb-3">
                                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                                            <div class="col-md-8 col-lg-9">

                                                                 <?php
                                                                 $this->getProfileImagePath($username);
                                                                 ?>

                                                                 <div class="pt-2">
                                                                      <!-- Tombol Hapus Gambar Profil -->
                                                                      <form action="edit-profile.php" method="post">
                                                                           <button type="submit" name="hapus_gambar" class="btn btn-danger btn-sm" title="Hapus foto profil"><i class="bi bi-trash"></i></button>
                                                                      </form>

                                                                      <!-- Form Unggah Gambar Profil -->
                                                                      <form action="edit-profile.php" method="post" enctype="multipart/form-data">
                                                                           <input type="file" name="file_gambar" id="file_gambar">
                                                                           <button type="submit" class="btn btn-primary btn-sm" title="Unggah foto baru"><i class="bi bi-upload"></i></button>
                                                                      </form>
                                                                 </div>
                                                            </div>
                                                       </div>
                                                  </form><!-- End Profile Image Edit Form -->

                                                  <?php
                                                  $this->getUserById($username);
                                                  ?>

                                                  <!-- Profile Edit Form -->
                                                  <form method="post" action="edit-profile.php" to action="edit-profile.php?username=<?php echo $id_dosen ?>">
                                                       <div class="row mb-3">
                                                            <label for="nama" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                                                            <div class="col-md-8 col-lg-9">
                                                                 <input name="nama" type="text" class="form-control" id="nama" value="<?php echo $nama_dosen ?>">
                                                            </div>
                                                       </div>

                                                       <?php
                                                       $this->getUserData($username);
                                                       ?>

                                                       <div class="text-center">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                       </div>
                                                  </form>
                                             </div>

                                             <div class="tab-pane fade pt-3" id="profile-change-password">
                                                  <h5 class="card-title">Change Password</h5>
                                                  <form method="post" action="edit_password.php" enctype="multipart/form-data">
                                                       <div class="row mb-3">
                                                            <label for="password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                                            <div class="col-md-8 col-lg-9">
                                                                 <input name="password" type="password" class="form-control" id="password">
                                                            </div>
                                                       </div>

                                                       <div class="text-center">
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                       </div>
                                                  </form>
                                             </div>

                                        </div>

                                   </div>
                              </div>

                         </div>
                    </div>
               </section>

          </main>
<?php
          $output = ob_get_clean(); // Ambil output yang ditangkap dan bersihkan penangkapan
          return $output;
     }
}
$profileDosen = new ProfileDosen();
?>