//
//  SplashVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/20/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import SideMenuController
import Crashlytics

class SplashVC: UIViewController {

    var count : Int = 4

    @IBOutlet weak var progressV: UIProgressView!
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
        _ = Timer.scheduledTimer(timeInterval: 1, target: self, selector: #selector(update), userInfo: nil, repeats: true)

    }

    @objc func update() {
        if(count > 0) {
            progressV.setProgress(Float(count), animated: true)
            count -= 1
        }
        else{
            showVC()
        }
    }
    
    func showVC()  {
        if UserDefaults.standard.bool(forKey: "login") {
            
            
            let mainVC =  self.storyboard?.instantiateViewController(withIdentifier: "homeNC") as! UINavigationController
//            self.present(mainVC, animated: true, completion: nil)
            
            let sideMenuVC =  self.storyboard?.instantiateViewController(withIdentifier: "sideMenuVC") as! SideMenuVC
            let sideMenuViewController = SideMenuController()
            // embed the side and center controllers
            sideMenuViewController.embed(sideViewController: sideMenuVC)
            sideMenuViewController.embed(centerViewController: mainVC)
            
            show(sideMenuViewController, sender: nil)
            
        }
        else{
            
            let loginVC =  self.storyboard?.instantiateViewController(withIdentifier: "loginVC") as! LoginVC
            self.present(loginVC, animated: true, completion: nil)
            
        }
    }
}
