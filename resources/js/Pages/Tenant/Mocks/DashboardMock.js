import { NotebookPen, Check, PenLine, Eye } from "lucide-react"

export const dashboardMock = {
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