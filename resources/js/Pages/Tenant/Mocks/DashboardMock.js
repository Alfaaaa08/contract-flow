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
}