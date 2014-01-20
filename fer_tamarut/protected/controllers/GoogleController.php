<?php
require_once 'Google_Client.php';
require_once 'Google_PlusService.php';
date_default_timezone_set('Europe/Zagreb');
session_start();

class GoogleController extends Controller
{
    public $status=0;
    
	public function actionIndex()
	{
	     $client = new Google_Client();
            $client->setApplicationName('fer_tamarut');

            $client->setClientId('161810351426.apps.googleusercontent.com');
            $client->setClientSecret('V1PKrTtMirBmV3h2F-ViAWI5');
            //$client->setRedirectUri('https://localhost/series/googleplus/');
			$client->setRedirectUri('https://localhost/fer_tamarut/index.php/google/');
            $client->setDeveloperKey('AIzaSyAH8LluLHEzkf9n59ehp7Q2nR0ZcL0DkA0');
            $plus = new Google_PlusService($client);

            if (isset($_GET['code'])) {
                $client->authenticate();
                $_SESSION['token'] = $client->getAccessToken();
                $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }

            if (isset($_SESSION['token'])) {
                $client->setAccessToken($_SESSION['token']);
            }

            if ($client->getAccessToken()) {
		        $this->render('index',array('status'=>0));
			$_SESSION['token'] = $client->getAccessToken();
            } else {
                $authUrl = $client->createAuthUrl();
                print "<a href='$authUrl'>Connect Me!</a>";
			}
	}

    public function actionActivity()
    {
	    $pomoc=$_POST['broj_activity'];
		if (!is_numeric($pomoc))
		    $pomoc=-1;
	    if($pomoc>=0&&$pomoc<91){
            $client = new Google_Client();
            $client->setApplicationName('fer_tamarut');

            $client->setClientId('161810351426.apps.googleusercontent.com');
            $client->setClientSecret('V1PKrTtMirBmV3h2F-ViAWI5');
            //$client->setRedirectUri('https://localhost/series/googleplus/');
			$client->setRedirectUri('https://localhost/fer_tamarut/index.php/google/');
            $client->setDeveloperKey('AIzaSyAH8LluLHEzkf9n59ehp7Q2nR0ZcL0DkA0');
            $plus = new Google_PlusService($client);

            if (isset($_GET['code'])) {
                $client->authenticate();
                $_SESSION['token'] = $client->getAccessToken();
                $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }

            if (isset($_SESSION['token'])) {
                $client->setAccessToken($_SESSION['token']);
            }

            if ($client->getAccessToken()) {
                $a=$plus->people->get('me');
                $korisnik_id=$a['id'];
				
				$imenaprijatelja= array();
				$plusevi=array();
				$komentari=array();
				$objave=array();
				$podaci=Googleactivity::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			    $friends=Googlefriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			
			    if(count($podaci)==0){  
				    $this->status=1;
				    $this->actionRefresh();
		            //$this->render('activity',array('status'=>1));
			    } else {
			        foreach ($friends as $a){
					    $imenaprijatelja[$a->id]=$a->prijatelj;
					}
			        $datumbaza=$a->datee;
					$danas=date('Y-m-j');
					if($datumbaza==$danas){
				        $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danas) ) ;
				        foreach ($podaci as $a){
                            $datumobjekta= strtotime($a->vrijeme);
							if($datumobjekta>=$zeljenidatum){
						        $b=$a->vlasnik;
				                if (array_key_exists($b,$imenaprijatelja)&&$b!=$korisnik_id){
				                    if (array_key_exists($b,$objave))
                  		                $objave[$b]+=1;
                                    else
                                        $objave[$b]=1;					
				                } 
								$b=$a->plusevi;
				                if ($b!=0){
				                    $c=explode(",",$b);
				                    foreach($c as $d){
					                    if (array_key_exists($d,$imenaprijatelja)&&$d!=$korisnik_id){
				                            if (array_key_exists($d,$plusevi))
                  		                        $plusevi[$d]+=1;
                                            else
                                                $plusevi[$d]=1;					
				                        }
					                }  
				                }
								$b=$a->komentari;
				                if ($b!=0){
				                    $c=explode(",",$b);
				                    foreach($c as $d){
					                    if (array_key_exists($d,$imenaprijatelja)&&$d!=$korisnik_id){
				                            if (array_key_exists($d,$komentari))
                  		                        $komentari[$d]+=1;
                                            else
                                                $komentari[$d]=1;					
				                        }
					                }  
				                }
							}
						}
						$a=array_keys($plusevi);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$objave)))
					            $objave[$b]=0;
				        }
				        $a=array_keys($komentari);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$objave)))
					            $objave[$b]=0;
				        }
				        $a=array_keys($objave);
				        foreach ($a as $b){
				            if((!array_key_exists($b,$plusevi)))
					            $plusevi[$b]=0;
				            if((!array_key_exists($b,$komentari)))
					            $komentari[$b]=0;
				        }     
				        arsort($objave);
				        $this->render('activity',array('status'=>0,'imena'=>$imenaprijatelja,'objave'=>$objave,'plusevi'=>$plusevi,'komentari'=>$komentari));    
				        				
				    } else {
					    $this->status=3;
						$this->actionRefresh();
					    //$this->render('activity',array('status'=>3));			
			           
					}
				}

                $_SESSION['token'] = $client->getAccessToken();
            } else {
                $authUrl = $client->createAuthUrl();
                print "<a href='$authUrl'>Connect Me!</a>";
            }
        } else {
		   $this->render('activity',array('status'=>2));
	    }
    }	
	
	
	public function actionInterests()
	{
	    $pomoc=$_POST['rijeci'];
		if(strlen($pomoc)>0){
		    $client = new Google_Client();
            $client->setApplicationName('fer_tamarut');

            $client->setClientId('161810351426.apps.googleusercontent.com');
            $client->setClientSecret('V1PKrTtMirBmV3h2F-ViAWI5');
            $client->setRedirectUri('https://localhost/fer_tamarut/index.php/google/');
            $client->setDeveloperKey('AIzaSyAH8LluLHEzkf9n59ehp7Q2nR0ZcL0DkA0');
            $plus = new Google_PlusService($client);

            if (isset($_GET['code'])) {
                $client->authenticate();
                $_SESSION['token'] = $client->getAccessToken();
                $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
                header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            }

            if (isset($_SESSION['token'])) {
                $client->setAccessToken($_SESSION['token']);
            }

            if ($client->getAccessToken()) {
                $a=$plus->people->get('me');
                $korisnik_id=$a['id'];
		
		        $osobe= array();
				$stranice=array();
		        $imenaprijatelja= array();
		        $podaci=Googleinterests::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
			    $friends=Googlefriends::model()->findAllByAttributes(array('user_id'=>"$korisnik_id"));
				
				if(count($podaci)==0){  
				    $this->status=1;
					$this->actionRefresh();
		           // $this->render('interests',array('status'=>1));
			    } else {
			        foreach ($friends as $a){
					    $imenaprijatelja[$a->id]=$a->prijatelj;
					}
			        $datumbaza=$a->datee;
					$danas=date('Y-m-j');
					if($datumbaza==$danas){
					    $rijeci=explode(",", $pomoc);
				        $kljucne_rijeci = array ();
				        $i=0;
				        foreach($rijeci as $a){
	                        $a=trim($a);			 
     				        if(strlen($a)>0){
					            $a= strtolower($a); 
				                $kljucne_rijeci[$i]=$a;
						        $i++;
					        }
				        }
						foreach ($podaci as $a){
					        $opis=$a->opis_objekta;
                            $opis= strtolower($opis);
							foreach($kljucne_rijeci as $rijec){
                                if(strpos($opis, $rijec) !== false){
								    if($a->tip_objekta=='person'){
                                        $osobe[$a->id_objekta]=$a->opis_objekta;									
								    }else{
									    $stranice[$a->id_objekta]=$a->opis_objekta;
						            }
									break;
								}
							}
                        }	
						$this->render('interests',array('status'=>0,'imena'=>$imenaprijatelja,'osobe'=>$osobe,'stranice'=>$stranice));
					} else {
					    $this->status=3;
						$this->actionRefresh();
					    //$this->render('interests',array('status'=>3));			
			        }
	     
                }		
		    
                $_SESSION['token'] = $client->getAccessToken();
            } else {
                $authUrl = $client->createAuthUrl();
                print "<a href='$authUrl'>Connect Me!</a>";
            } 
		
		} else {
		      $this->render('interests',array('status'=>2));
		}	
	}
	
	
	public function actionRefresh()
	{
	
        // Set your cached access token. Remember to replace $_SESSION with a
        // real database or memcached.
       

        $client = new Google_Client();
        $client->setApplicationName('fer_tamarut');

        $client->setClientId('161810351426.apps.googleusercontent.com');
        $client->setClientSecret('V1PKrTtMirBmV3h2F-ViAWI5');
        $client->setRedirectUri('https://localhost/fer_tamarut/index.php/google/');
        $client->setDeveloperKey('AIzaSyAH8LluLHEzkf9n59ehp7Q2nR0ZcL0DkA0');
        $plus = new Google_PlusService($client);

        if (isset($_GET['code'])) {
            $client->authenticate();
            $_SESSION['token'] = $client->getAccessToken();
            $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

        if ($client->getAccessToken()) {
	
		    $a=$plus->people->get('me');
            $korisnik_id=$a['id'];
	
            $criteria = new EMongoCriteria;
            $criteria->user_id = $korisnik_id;

        	//interesi
		    $a=$plus->people->listPeople('me','visible');
		    
		   
		    $tip_objekta=array ();
			$opis_objekta=array();
			$imena_prijatelja = array();
			
		    $paging=1;
		    while($paging==1){
		        $paging=0;
				
				if(array_key_exists('nextPageToken',$a)){
				    $token=$a['nextPageToken'];
					$paging=1;
		        }
				foreach($a['items'] as $petljaa){
				    $tip_objekta[$petljaa['id']]=$petljaa['objectType'];
				    $imena_prijatelja[$petljaa['id']]=$petljaa['displayName'];			
				}
                if($paging==1)
                    $a=$plus->people->listPeople('me','visible',array('pageToken' => $token));      				
 			
			}
            
            $objekti=array_keys($imena_prijatelja);			
		    foreach($objekti as $id){
                $a=$plus->people->get("$id");
				if(array_key_exists('aboutMe',$a)){
				    $opis_objekta[$a['id']]=$a['aboutMe'];
				} else {
				    $opis_objekta[$a['id']]=0;
                }
            }			
		
		    
		   
		    $result = Googleinterests::model()->deleteAll($criteria);
		    $a=array_keys($imena_prijatelja);
		    foreach ($a as $b){
				$bd = new Googleinterests();
                $bd->user_id="$korisnik_id";
               	$bd->datee=date('Y-m-j');
			    $bd->id_objekta="$b";
				$bd->tip_objekta="$tip_objekta[$b]";
				$bd->opis_objekta="$opis_objekta[$b]";
				$bd->save();
            }
		   		
            
            $vrijeme_aktivnosti = array();
            $plusoners_aktivnosti = array();
            $komentari_aktivnosti = array();
            $vlasnik_aktivnosti = array();

			$pomoc=90;
		    $danasnjidatum = date('Y-m-j');
            $zeljenidatum = strtotime ( "-$pomoc day" , strtotime ( $danasnjidatum ) ) ;
				
        
            $data = $plus->activities->listActivities('me','public');
            
			$paging=1;
			$istek_vremena=0;
		    while($paging==1&&$istek_vremena==0){
		        $paging=0;
				
				if(array_key_exists('nextPageToken',$data)){
				    $token=$data['nextPageToken'];
					$paging=1;
		        }

				
                foreach($data['items'] as $petljaa){
                    $vrijeme_objekta=$petljaa['published'];
				    $vrijeme_objekta=strtotime($vrijeme_objekta);
				    if($vrijeme_objekta>=$zeljenidatum){
				        $vrijeme_aktivnosti[$petljaa['id']]=$petljaa['published'];
	                    $vlasnik_aktivnosti[$petljaa['id']]=$petljaa['actor']['id'];
	                    if(!array_key_exists($petljaa['actor']['id'],$imena_prijatelja))
	                        $imena_prijatelja[$petljaa['actor']['id']]=$petljaa['actor']['displayName'];
                        if($petljaa['object']['replies']['totalItems']>0){
	                        $a= $plus->comments->listComments($petljaa['id']);
							$unutarnji_paging=1;
							while($unutarnji_paging==1){
		                        $unutarnji_paging=0;
								if(array_key_exists('nextPageToken',$a)){
				                    $token_komentari=$a['nextPageToken'];
					                $unutarnji_paging=1;
		                        }
							    foreach($a['items'] as $petljab){
		                            if(!array_key_exists($petljab['actor']['id'],$imena_prijatelja))
			                            $imena_prijatelja[$petljab['actor']['id']]=$petljab['actor']['displayName'];
		                            if(array_key_exists($petljaa['id'],$komentari_aktivnosti)){
			                            $komentari_aktivnosti[$petljaa['id']]=$komentari_aktivnosti[$petljaa['id']].",".$petljab['actor']['id'];
			                        } else {
			                            $komentari_aktivnosti[$petljaa['id']]=$petljab['actor']['id'];
			                        }
	                            } 
								if($unutarnji_paging==1)
								    $a= $plus->comments->listComments($petljaa['id'],array('pageToken' => $token_komentari));
								
							}
	                    } else{
	                        $komentari_aktivnosti[$petljaa['id']]=0;
	                    }
	                    if($petljaa['object']['plusoners']['totalItems']>0){
	                        $a=$plus->people->listByActivity($petljaa['id'],'plusoners');
							$unutarnji_paging=1;
							while($unutarnji_paging==1){
							    $unutarnji_paging=0;
								if(array_key_exists('nextPageToken',$a)){
				                    $token_plusevi=$a['nextPageToken'];
					                $unutarnji_paging=1;
		                        }
		                    
							    foreach($a['items'] as $petljab){
		                            if(!array_key_exists($petljab['id'],$imena_prijatelja))
			                            $imena_prijatelja[$petljab['id']]=$petljab['displayName'];
		                            if(array_key_exists($petljaa['id'],$plusoners_aktivnosti)){
			                            $plusoners_aktivnosti[$petljaa['id']]=$plusoners_aktivnosti[$petljaa['id']].",".$petljab['id'];
		                            } else {
			                            $plusoners_aktivnosti[$petljaa['id']]=$petljab['id'];
	                                }
	                            }
	                            if($unutarnji_paging==1)
								    $a= $plus->people->listByActivity($petljaa['id'],'plusoners', array('pageToken' => $token_plusevi));	
						    }
						} else {
	                        $plusoners_aktivnosti[$petljaa['id']]=0;
                        }
                    } else {
					    $istek_vremena=1;
						break;
					}
			    }
				
				if($paging==1)
                    $data=$plus->activities->listActivities('me','public',array('pageToken' => $token));      				
			}
			
            $criteria = new EMongoCriteria;
            $criteria->user_id = $korisnik_id;
            $result = Googleactivity::model()->deleteAll($criteria);
			
		    $a=array_keys($vrijeme_aktivnosti);
		    foreach ($a as $b){
				$bd = new Googleactivity();
                $bd->user_id="$korisnik_id";
               	$bd->datee=date('Y-m-j');
			    $bd->vlasnik="$vlasnik_aktivnosti[$b]";
				$bd->vrijeme="$vrijeme_aktivnosti[$b]";
				if(array_key_exists($b,$plusoners_aktivnosti))
				    $bd->plusevi=$plusoners_aktivnosti[$b];
				else
				    $bd->plusevi=0;
				if(array_key_exists($b,$komentari_aktivnosti))
				    $bd->komentari=$komentari_aktivnosti[$b];
				else
				    $bd->komentari=0;
				$bd->save();
            }
			
			
			
			
			
			
			
			$result = Googlefriends::model()->deleteAll($criteria);
		    $a=array_keys($imena_prijatelja);
		    foreach ($a as $b){
				$bd = new Googlefriends();
                $bd->user_id="$korisnik_id";
               	$bd->datee=date('Y-m-j');
			    $bd->id="$b";
				$bd->prijatelj="$imena_prijatelja[$b]";
				$bd->save();
            }
            $this->render('index',array('status'=>$this->status));
		    $this->status=0;
 
        $_SESSION['token'] = $client->getAccessToken();
    } else {
        $authUrl = $client->createAuthUrl();
        print "<a href='$authUrl'>Connect Me!</a>";
    }
	
	
	
	
	}
	
	
	
	
	public function actionClearsessions(){
 
/* Load and clear sessions */

session_destroy();
$this->render('index',array('status'=>0));
 


}
	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	
	
    function recursion($data, $razmak, $kljuc) {
        foreach ($data as $a => $b) {
            if (is_array($b)) {
                $this->recursion($b, "&nbsp&nbsp" . $razmak, $kljuc . "['$a']");
            } else {
                echo "{$razmak} {$kljuc}['$a'] = $b<br>";
            }
        }
    }
}