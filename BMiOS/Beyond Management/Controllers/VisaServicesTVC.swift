//
//  VisaServicesTVC.swift
//  Beyond Management
//
//  Created by Mamdouh El Nakeeb on 12/5/17.
//  Copyright © 2017 Beyond Management. All rights reserved.
//

import UIKit

class VisaServicesTVC: UITableViewController {

    var visaArr = Array<Visa>()
    
    override func viewDidLoad() {
        super.viewDidLoad()

        visaArr.append(Visa.init(id: 0, name: "VISA B1", imgUrl: "", info: "Test Info", basicPrice: 0, basicInfo: "", interPrice: 0, interInfo: "", advPrice: 0, advInfo: ""))
        
        visaArr.append(Visa.init(id: 0, name: "VISA B1", imgUrl: "", info: "Test Info", basicPrice: 0, basicInfo: "", interPrice: 0, interInfo: "", advPrice: 0, advInfo: ""))
        
        visaArr.append(Visa.init(id: 0, name: "VISA B1", imgUrl: "", info: "Test Info", basicPrice: 0, basicInfo: "", interPrice: 0, interInfo: "", advPrice: 0, advInfo: ""))
        
    }

    override func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        // #warning Incomplete implementation, return the number of rows
        return 3
    }

    override func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 150
    }
    
    override func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "visaCell", for: indexPath) as! VisaServicesTVCell

        // Configure the cell...
        
        cell.visaLbl.text = visaArr[indexPath.row].name
        cell.visaIV.image = UIImage(named: "visa")

        cell.layoutIfNeeded()
        return cell
    }

}
