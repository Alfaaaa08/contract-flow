import { NotebookPen, Check, PenLine, Eye } from "lucide-react"

export const dashboardMock = {
  cards: {
    activeContracts: 62,
    expiring30Days: 7,
    totalContractValue: 106227.29,
    clientsWithActiveContracts: 12,
    pipeline: {
      draft: 30,
      review: 15,
      signed: 55,
    }
  },
  overviewTable: [
    {
      contractName: "Car Renting",
      client: "James",
      value: 100.00,
      status: "Active",
      progress: 10
    },
    {
      contractName: "Warehouse Renting",
      client: "Global Corp",
      value: 1280.00,
      status: "Active",
      progress: 27
    },
    {
      contractName: "Supplier Agreement",
      client: "Angela",
      value: 720.00,
      status: "Expiring",
      progress: 90
    },
    {
      contractName: "Building construction",
      client: "Jhon",
      value: 560.50,
      status: "Expired",
      progress: 100
    },
    {
      contractName: "Office Lease",
      client: "Global Corp",
      value: 560.50,
      status: "Expired",
      progress: 100
    },
    {
      contractName: "App Development",
      client: "Tech Solutions",
      value: 139.79,
      status: "Draft",
      progress: 0
    },
  ],
  expiringContracts: [
    {
      contractName: "Supplier Agreement",
      client: "Angela",
      daysLeft: 5,
      color: "text-destructive"
    },
    {
      contractName: "Office Lease",
      client: "Global Corp",
      daysLeft: 12,
      color: "text-chart-4"
    },
    {
      contractName: "SaaS Subscription",
      client: "Tech Solutions",
      daysLeft: 24,
      color: "text-primary"
    },
  ],
  activities: [
    {
      user: "Angela",
      action: "signed",
      target: "Supplier Agreement",
      timestamp: "2h ago",
      icon: NotebookPen,
      iconColor: "text-blue-500",
    },
    {
      user: "James",
      action: "created",
      target: "'Car Renting' Contract",
      timestamp: "5h ago",
      icon: Check,
      iconColor: "text-green-500",
    },
    {
      user: "You",
      action: "updated",
      target: "App Development",
      timestamp: "Yesterday",
      icon: PenLine,
      iconColor: "text-amber-500",
    },
    {
      user: "Global Corp",
      action: "viewed",
      target: "Warehouse Renting",
      timestamp: "2 days ago",
      icon: Eye,
      iconColor: "text-primary",
    },
  ]
}