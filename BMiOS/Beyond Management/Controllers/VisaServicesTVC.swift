//
//  VisaServicesTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/5/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit
import SideMenu
import Alamofire
import SideMenuController

class VisaServicesTVC: UITableViewController{
    
    var visaArr = Array<Visa>()
    
    let indicator = UIActivityIndicatorView(activityIndicatorStyle: UIActivityIndicatorViewStyle.white)

    override func viewDidLoad() {
        super.viewDidLoad()

//        setupSideMenu()
    
        let barBtnItem = UIBarButtonItem(customView: indicator)
        self.navigationItem.rightBarButtonItem = barBtnItem
        
        getVisa()
        
//        showDropIn(clientTokenOrTokenizationKey: clientToken)
//        startCheckout()
    }

    @IBAction func sideMenuAction(_ sender: Any) {
        
        self.sideMenuController?.toggle()
        
    }
    
    func getVisa (){
        
        indicator.startAnimating()
        
        let params: Parameters = [
            "user_id": UserDefaults.standard.integer(forKey: "id")
        ]
        
        Alamofire.request(Urls.VISA_SERVICES, method: .get, parameters: params).responseJSON{
            
            response in
            
            print(response)
            
            if let result = response.result.value {
                
                let json = result as! NSDictionary
                
                if !(json.value(forKey: "error") as! Bool) {
                
                    let jsonArr = json.value(forKey: "services") as! NSArray
                    
                    for i in 0 ..< jsonArr.count {
                        let visaObj = jsonArr.object(at: i) as! NSDictionary
                        self.visaArr.append(Visa.init(id: Int(visaObj.value(forKey: "id") as! String)!,
                                                      name: visaObj.value(forKey: "name") as! String,
                                                      imgUrl: visaObj.value(forKey: "img") as! String,
                                                      info: visaObj.value(forKey: "info") as! String,
                                                      basicPrice: Int(visaObj.value(forKey: "basic_price") as! String)!,
                                                      basicInfo: visaObj.value(forKey: "basic_info") as! String,
                                                      interPrice: Int(visaObj.value(forKey: "inter_price") as! String)!,
                                                      interInfo: visaObj.value(forKey: "inter_info") as! String,
                                                      advPrice: Int(visaObj.value(forKey: "advanced_price") as! String)!,
                                                      advInfo: visaObj.value(forKey: "advanced_info") as! String))
                    }
                    
                    self.tableView.reloadData()
                    
                }
                else {
                    let alert = UIAlertController(title: "Error", message: json.value(forKey: "message") as? String, preferredStyle: UIAlertControllerStyle.alert)
                    alert.addAction(UIAlertAction(title: "OK", style: UIAlertActionStyle.default, handler: {action in
                        
                        UserDefaults.standard.set(false, forKey: "login")
                        let loginVC =  self.storyboard?.instantiateViewController(withIdentifier: "loginVC") as! LoginVC
                        self.present(loginVC, animated: true, completion: nil)
                        
                    }))
                    self.present(alert, animated: true, completion: nil)
                    
                    return
                }
                
            }
            
            self.indicator.stopAnimating()
        }
    }
    
    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return visaArr.count
    }

    override func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 150
    }
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "visaCell", for: indexPath) as! VisaServicesTVCell

        // Configure the cell...
        
        cell.visaLbl.text = visaArr[indexPath.row].name
        cell.visaIV.sd_setImage(with: URL(string: visaArr[indexPath.row].imgUrl), placeholderImage: UIImage(named: "visa"))

        cell.layoutIfNeeded()
        return cell
    }
    
    override func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {

        tableView.deselectRow(at: indexPath, animated: true)
        
        let visaOptionsTVC = self.storyboard?.instantiateViewController(withIdentifier: "visaOptions") as? VisaOptionsTVC
        visaOptionsTVC?.visa = visaArr[indexPath.row]
        self.navigationController?.pushViewController(visaOptionsTVC!, animated: true)
        
    }

    fileprivate func setupSideMenu() {
        // Define the menus
        SideMenuManager.default.menuLeftNavigationController = storyboard!.instantiateViewController(withIdentifier: "LeftMenuNavigationController") as? UISideMenuNavigationController
        //        SideMenuManager.default.menuRightNavigationController = storyboard!.instantiateViewController(withIdentifier: "RightMenuNavigationController") as? UISideMenuNavigationController
        
        // Enable gestures. The left and/or right menus must be set up above for these to work.
        // Note that these continue to work on the Navigation Controller independent of the View Controller it displays!
        SideMenuManager.default.menuAddPanGestureToPresent(toView: self.navigationController!.navigationBar)
        SideMenuManager.default.menuAddScreenEdgePanGesturesToPresent(toView: self.navigationController!.view)
        
        // Set up a cool background image for demo purposes
        SideMenuManager.default.menuAnimationBackgroundColor = UIColor(patternImage: UIImage(named: "bg")!)
        
        SideMenuManager.default.menuShadowOpacity = 50
        SideMenuManager.default.menuPresentMode = .viewSlideInOut
        SideMenuManager.default.menuFadeStatusBar = false
        SideMenuManager.default.menuAllowPushOfSameClassTwice = false
        SideMenuManager.default.menuPushStyle = .popWhenPossible
    }
    
}

extension VisaServicesTVC: UISideMenuNavigationControllerDelegate {
    
    func sideMenuWillAppear(menu: UISideMenuNavigationController, animated: Bool) {
        print("SideMenu Appearing! (animated: \(animated))")
    }
    
    func sideMenuDidAppear(menu: UISideMenuNavigationController, animated: Bool) {
        print("SideMenu Appeared! (animated: \(animated))")
    }
    
    func sideMenuWillDisappear(menu: UISideMenuNavigationController, animated: Bool) {
        print("SideMenu Disappearing! (animated: \(animated))")
    }
    
    func sideMenuDidDisappear(menu: UISideMenuNavigationController, animated: Bool) {
        print("SideMenu Disappeared! (animated: \(animated))")
    }
    
}

