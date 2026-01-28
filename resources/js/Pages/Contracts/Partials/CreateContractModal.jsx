import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/Components/ui/dialog";

import { Label } from "@/Components/ui/label";

import { Input } from "@/Components/ui/input";

import { UploadCloud } from "lucide-react";

export default function CreateContractModal({ open, onOpenChange }) {
  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-[600px] bg-card border-border">
        <DialogHeader>
          <DialogTitle className="text-xl font-bold text-foreground">Create New Contract</DialogTitle>
        </DialogHeader>

        <div className="grid grid-cols-2 gap-4 py-4">
          <div className="col-span-2 space-y-2">
            <Label htmlFor="name">Contract Name</Label>
            <Input id="name" placeholder="e.g. 2026 Software License" className="bg-background" />
          </div>

          <div className="space-y-2">
            <Label htmlFor="client">Client</Label>
            <Input id="client" placeholder="Select client..." className="bg-background" />
          </div>

          <div className="space-y-2">
            <Label htmlFor="type">Contract Type</Label>
            <Input id="type" placeholder="e.g. Service" className="bg-background" />
          </div>

          <div className="space-y-2">
            <Label htmlFor="value">Value ($)</Label>
            <Input id="value" type="number" placeholder="0.00" className="bg-background" />
          </div>

          <div className="space-y-2">
            <Label htmlFor="date">End Date</Label>
            <Input id="date" type="date" className="bg-background" />
          </div>

          <div className="col-span-2 pt-2">
            <Label>Upload Document (PDF)</Label>
            <div className="mt-2 border-2 border-dashed border-border rounded-lg p-8 flex flex-col items-center justify-center bg-background/50 hover:bg-accent/5 transition-colors cursor-pointer">
              <UploadCloud className="h-8 w-8 text-muted-foreground mb-2" />
              <p className="text-sm text-muted-foreground">Click to upload or drag and drop</p>
            </div>
          </div>
        </div>

        <DialogFooter>
          <button 
            onClick={() => onOpenChange(false)}
            className="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-foreground"
          >
            Cancel
          </button>
          <button className="bg-primary text-primary-foreground px-4 py-2 rounded-md text-sm font-semibold hover:opacity-90 transition-all">
            Save Contract
          </button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}